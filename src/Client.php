<?php

namespace Kameli\Quickpay;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Kameli\Quickpay\Exceptions\UnauthorizedException;
use Kameli\Quickpay\Exceptions\ValidationException;

class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @param \GuzzleHttp\Client $client
     * @param string $apiKey
     */
    public function __construct(GuzzleClient $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function request($method, $path, $parameters = [])
    {
        $url = Quickpay::API_URL . ltrim($path, '/');

        try {
            if (method_exists($this->client, 'request')) {
                $response = $this->client->request($method, $url, [
                    'auth' => ['', $this->apiKey],
                    'headers' => [
                        'Accept-Version' => 'v10',
                        'Accept' => 'application/json',
                    ],
                    'form_params' => $parameters,
                ])->getBody()->getContents();
            } else {
                $response = $this->client->send($this->client->createRequest($method, $url, [
                    'auth' => ['', $this->apiKey],
                    'headers' => [
                        'Accept-Version' => 'v10',
                        'Accept' => 'application/json',
                    ],
                    'body' => $parameters,
                ]))->getBody()->getContents();
            }
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());

            if (isset($response->message)) {
                if (strpos($response->message, 'Invalid API key') === 0) {
                    throw new UnauthorizedException($response->message);
                }

                if (strpos($response->message, 'Not authorized') === 0) {
                    throw new UnauthorizedException($response->message);
                }

                if (strpos($response->message, 'Validation error') === 0) {
                    throw new ValidationException($response->errors);
                }

                throw new Exception($response->message);
            } elseif (isset($response->error)) {
                throw new Exception($response->error);
            } else {
                throw new Exception($e->getMessage());
            }
        }

        return json_decode($response);
    }
}
