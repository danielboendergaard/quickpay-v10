<?php

namespace Kameli\Quickpay;

use GuzzleHttp\Client as GuzzleClient;

class Quickpay
{
    const API_URL = 'https://api.quickpay.net/';
    const API_VERSION = '10';

    /**
     * @var \Kameli\Quickpay\Client
     */
    protected $client;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @param string $apiKey
     * @param string $privateKey
     */
    public function __construct($apiKey, $privateKey = null)
    {
        $this->client = new Client(new GuzzleClient, $apiKey);
        $this->privateKey = $privateKey;
    }

    /**
     * @return \Kameli\Quickpay\Callback
     */
    public function callbacks()
    {
        return new Callback($this->client, $this->privateKey);
    }

    /**
     * @return \Kameli\Quickpay\Subscription
     */
    public function subscriptions()
    {
        return new Subscription($this->client);
    }

    /**
     * @return \Kameli\Quickpay\Payment
     */
    public function payments()
    {
        return new Payment($this->client);
    }
}