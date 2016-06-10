<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Client;

abstract class Service
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
     * Create a collection of data sets
     * @param array $collection
     * @param string $class
     * @return array
     */
    protected function createCollection($collection, $class)
    {
        return array_map(function ($data) use ($class) {
            return new $class($data);
        }, $collection);
    }
}
