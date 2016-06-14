<?php

namespace Kameli\Quickpay;

use GuzzleHttp\Client as GuzzleClient;
use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Entities\Subscription;
use Kameli\Quickpay\Exceptions\InvalidCallbackException;
use Kameli\Quickpay\Services\Callbacks;
use Kameli\Quickpay\Services\Payments;
use Kameli\Quickpay\Services\Subscriptions;
use Symfony\Component\HttpFoundation\Request;

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
        return new Callbacks($this->client);
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
    public function receivePaymentCallback(Request $request = null)
    {
        return new Payment($this->receiveCallback($request));
    }

    /**
     * Receive the callback request and return the subscription
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function receiveSubscriptionCallback(Request $request = null)
    {
        return new Subscription($this->receiveCallback($request));
    }

    /**
     * Receive the callback request and return the response
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return object
     * @throws \Kameli\Quickpay\Exceptions\InvalidCallbackException
     */
    protected function receiveCallback(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();

        if (! $this->validateCallback($request)) {
            throw new InvalidCallbackException('The callback request is invalid');
        }

        return json_decode($request->getContent());
    }

    /**
     * Validate a callback request
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     * @throws \Kameli\Quickpay\Exceptions\InvalidCallbackException
     */
    public function validateCallback(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();
        
        if (! isset($this->privateKey)) {
            throw new InvalidCallbackException('privateKey must be set to validate a callback');
        }

        $checksum = hash_hmac('sha256', $request->getContent(), $this->privateKey);

        return $checksum === $request->headers->get('QuickPay-Checksum-Sha256');
    }
}
