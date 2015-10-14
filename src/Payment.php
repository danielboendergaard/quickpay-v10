<?php

namespace Kameli\Quickpay;

class Payment
{
    /**
     * @var \Kameli\Quickpay\Client
     */
    protected $client;

    /**
     * @param \Kameli\Quickpay\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all payments
     * @return array
     */
    public function all()
    {
        return $this->client->request('GET', '/payments');
    }

    /**
     * Get payment
     * @param int $id
     * @return object
     */
    public function get($id)
    {
        return $this->client->request('GET', "/payments/{$id}");
    }

    /**
     * Create a payment
     * Required parameters: currency, order_id
     * @param array $parameters
     * @return object
     */
    public function create($parameters)
    {
        return $this->client->request('POST', '/payments', $parameters);
    }

    /**
     * Create/update a payment link
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return object
     */
    public function link($id, $parameters)
    {
        return $this->client->request('PUT', "/payments/{$id}/link", $parameters);
    }
}