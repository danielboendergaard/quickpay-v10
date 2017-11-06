<?php

namespace Kameli\Quickpay;

use Kameli\Quickpay\Exceptions\NotFoundException;
use Kameli\Quickpay\Exceptions\QuickpayException;
use Kameli\Quickpay\Exceptions\UnauthorizedException;
use Kameli\Quickpay\Exceptions\ValidationException;

class Client
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var resource
     */
    protected $curl;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->initializeCurl();
    }

    protected function initializeCurl()
    {
        $this->curl = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HEADER => true,
            CURLOPT_USERPWD => ":{$this->apiKey}",
            CURLOPT_HTTPHEADER => [
                'Accept-Version: v10',
                'Accept: application/json',
            ]
        ];

        curl_setopt_array($this->curl, $options);
    }

    /**
     * Make a request to the Quickpay API
     * @param string $method
     * @param string $path
     * @param array|null $parameters
     * @param bool $raw
     * @return mixed
     * @throws \Kameli\Quickpay\Exceptions\NotFoundException
     * @throws \Kameli\Quickpay\Exceptions\QuickpayException
     * @throws \Kameli\Quickpay\Exceptions\UnauthorizedException
     * @throws \Kameli\Quickpay\Exceptions\ValidationException
     */
    public function request($method, $path, $parameters = [], $raw = false)
    {
        $url = Quickpay::API_URL . ltrim($path, '/');

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_URL, $url);

        if ($parameters) {
            $files = count(array_filter($parameters, function ($parameter) {
                return $parameter instanceof \CURLFile;
            }));

            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $files ? $parameters : http_build_query($parameters, '', '&'));
        }

        $response = curl_exec($this->curl);

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $body = substr($response, - curl_getinfo($this->curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD));
        $json = json_decode($body);

        switch ($statusCode) {
            case 200:
            case 201:
            case 202:
                if ($raw) {
                    return $body;
                }

                return $json;
            case 400:
                throw new ValidationException($json->message, (array) $json->errors, $json->error_code);
            case 401:
                throw new UnauthorizedException($json->message);
            case 404:
                if (isset($json->message)) {
                    throw new NotFoundException($json->message);
                } elseif (isset($json->error)) {
                    throw new NotFoundException($json->error);
                }

                throw new NotFoundException(json_encode($json));
        }

        throw new QuickpayException('An invalid response was received from Quickpay', $response, $statusCode);
    }

    /**
     * Make a request to the Quickpay API and get the response as text
     * @param string $method
     * @param string $path
     * @param array|null $parameters
     * @return mixed
     * @throws \Kameli\Quickpay\Exceptions\NotFoundException
     * @throws \Kameli\Quickpay\Exceptions\QuickpayException
     * @throws \Kameli\Quickpay\Exceptions\UnauthorizedException
     * @throws \Kameli\Quickpay\Exceptions\ValidationException
     */
    public function requestRaw($method, $path, $parameters = [])
    {
        return $this->request($method, $path, $parameters, true);
    }
}
