<?php

namespace Kameli\Quickpay;

class Subscription
{
    /**
     * @var \Kameli\Quickpay\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function all()
    {
        return $this->client->request('GET', '/subscriptions');
    }

    public function create($parameters)
    {
        return $this->client->request('POST', '/subscriptions', $parameters);
    }
}