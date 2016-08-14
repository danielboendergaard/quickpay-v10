<?php

namespace Kameli\Quickpay;

use Exception;
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
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     * @throws \Kameli\Quickpay\Exceptions\UnauthorizedException
     * @throws \Kameli\Quickpay\Exceptions\ValidationException
     */
    public function request($method, $path, $parameters = [])
    {
        $url = Quickpay::API_URL . ltrim($path, '/');

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_URL, $url);

        if ($parameters) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($parameters, '', '&'));
        }

        $response = curl_exec($this->curl);

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $body = json_decode(substr($response, - curl_getinfo($this->curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD)));

        if (in_array($statusCode, [200, 201, 202])) {
            return $body;
        }

        switch ($statusCode) {
            case 400:
                throw new ValidationException($body->message, $body->errors, $body->error_code);
            case 401:
                throw new UnauthorizedException($body->message);
        }

        throw new Exception(json_encode($body));
    }
}
