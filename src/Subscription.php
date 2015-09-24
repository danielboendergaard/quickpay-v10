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

    public function create(array $parameters)
    {
        return $this->client->request('POST', '/subscriptions', $parameters);
    }

    public function authorize($id, $cardToken)
    {
        return $this->client->request('POST', "/subscriptions/{$id}/authorize?synchronized", [
            'amount' => 0,
            'card[token]' => $cardToken,
        ]);
    }

    public function recurring($id, $parameters)
    {
        return $this->client->request('POST', "/subscriptions/{$id}/recurring?synchronized", $parameters);
    }
}