<?php

namespace Kameli\Quickpay;

class Subscription
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
     * Get all subscriptions
     * @return array
     */
    public function all()
    {
        return $this->client->request('GET', '/subscriptions');
    }

    /**
     * Create a subscription
     * Required parameters: order_id, currency, description
     * @param array $parameters
     * @return object
     */
    public function create($parameters)
    {
        return $this->client->request('POST', '/subscriptions', $parameters);
    }

    /**
     * Authorize a subscription
     * @param int $id
     * @param string $cardToken
     * @return object
     */
    public function authorize($id, $cardToken)
    {
        return $this->client->request('POST', "/subscriptions/{$id}/authorize?synchronized", [
            'amount' => 0,
            'card[token]' => $cardToken,
        ]);
    }

    /**
     * Create a subscription recurring payment
     * Required parameters: amount, order_id
     * @param int $id
     * @param array $parameters
     * @return object
     */
    public function recurring($id, $parameters)
    {
        return $this->client->request('POST', "/subscriptions/{$id}/recurring?synchronized", $parameters);
    }

    /**
     * Create/update a subscription link
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return object
     */
    public function link($id, $parameters)
    {
        return $this->client->request('PUT', "/subscriptions/{$id}/link", $parameters);
    }
}