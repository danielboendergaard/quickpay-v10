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
     * @param \Kameli\Quickpay\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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

    /**
     * Encode variables to JSON
     * @param array $parameters
     * @return array mixed
     */
    protected function encodeVariables($parameters)
    {
        if (isset($parameters['variables'])) {
            $parameters['variables'] = array_map('json_encode', $parameters['variables']);
        }

        return $parameters;
    }
}
