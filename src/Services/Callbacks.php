<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Client;

class Callbacks
{
    /**
     * @var \Kameli\Quickpay\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @param \Kameli\Quickpay\Client $client
     * @param string $privateKey
     */
    public function __construct(Client $client, $privateKey = null)
    {
        $this->client = $client;
        $this->privateKey = $privateKey;
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