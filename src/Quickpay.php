<?php

namespace Kameli\Quickpay;

use GuzzleHttp\Client as GuzzleClient;
use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Services\Callbacks;
use Kameli\Quickpay\Services\Payments;
use Kameli\Quickpay\Services\Subscriptions;
use Symfony\Component\HttpFoundation\Request;
use UnexpectedValueException;

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
     * @return \Kameli\Quickpay\Services\Callbacks
     */
    public function callbacks()
    {
        return new Callbacks($this->client, $this->privateKey);
    }
    
    /**
     * @return \Kameli\Quickpay\Services\Payments
     */
    public function payments()
    {
        return new Payments($this->client);
    }
    
    /**
     * @return \Kameli\Quickpay\Services\Subscriptions
     */
    public function subscriptions()
    {
        return new Subscriptions($this->client);
    }

    /**
     * Receive the callback request and return the payment
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function receiveCallback(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();

        if (! $this->validateCallback($request)) {
            throw new UnexpectedValueException('The callback request is invalid');
        }

        return new Payment(json_decode($request->getContent()));
    }

    /**
     * Validate a callback request
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function validateCallback(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();
        
        if (! isset($this->privateKey)) {
            throw new UnexpectedValueException('privateKey must be set to validate a callback');
        }

        $checksum = hash_hmac('sha256', $request->getContent(), $this->privateKey);

        return $checksum === $request->headers->get('QuickPay-Checksum-Sha256');
    }
}