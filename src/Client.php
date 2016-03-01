<?php

namespace Kameli\Quickpay;

use ErrorException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

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
        $url = Quickpay::API_URL . $path;

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
                if (strpos($response->message, 'Validation error') !== null) {
                    throw new ValidationException($response->errors, $response->message, $e->getCode(), $e);
                }

                throw new ErrorException($response->message);
                
            } elseif (isset($response->error)) {
                throw new ErrorException($response->error);
            } else {
                throw new ErrorException($e->getMessage());
            }
        }

        return json_decode($response);
    }
}