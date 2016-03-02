<?php

namespace Kameli\Quickpay;

class Callback
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
     * Get failed and queued callbacks
     * @return array
     */
    public function all()
    {
        return $this->client->request('GET', '/callbacks');
    }

    /**
     * Retry failed callback
     * @param int $id
     * @return object
     */
    public function retry($id)
    {
        return $this->client->request('PATCH', "/callbacks/{$id}/retry");
    }
}