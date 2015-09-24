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
            $response = $this->client->request($method, $url, [
                'auth' => ['', $this->apiKey],
                'headers' => [
                    'Accept-Version' => 'v10',
                    'Accept' => 'application/json',
                ],
                'form_params' => $parameters,
            ])->getBody()->getContents();
        } catch (ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());

            throw new ErrorException($response->message);
        }

        return json_decode($response);
    }
}