<?php

namespace Kameli\Quickpay;

use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Entities\Subscription;
use Kameli\Quickpay\Exceptions\InvalidCallbackException;
use Kameli\Quickpay\Services\Callbacks;
use Kameli\Quickpay\Services\Payments;
use Kameli\Quickpay\Services\Subscriptions;

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
        $this->client = new Client($apiKey);
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
     * @param string $requestBody
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function receivePaymentCallback($requestBody = null)
    {
        return new Payment($this->receiveCallback($requestBody));
    }

    /**
     * Receive the callback request and return the subscription
     * @param string $requestBody
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function receiveSubscriptionCallback($requestBody = null)
    {
        return new Subscription($this->receiveCallback($requestBody));
    }

    /**
     * Receive the callback request and return the response
     * @param string $requestBody
     * @return object
     * @throws \Kameli\Quickpay\Exceptions\InvalidCallbackException
     */
    protected function receiveCallback($requestBody = null)
    {
        $requestBody = $requestBody ?: file_get_contents('php://input');

        if (! $this->validateCallback($requestBody)) {
            throw new InvalidCallbackException('The callback request is invalid');
        }

        return json_decode($requestBody);
    }

    /**
     * Validate a callback request
     * @param string $requestBody
     * @return bool
     * @throws \Kameli\Quickpay\Exceptions\InvalidCallbackException
     */
    public function validateCallback($requestBody = null)
    {
        $requestBody = $requestBody ?: file_get_contents('php://input');

        if (! isset($this->privateKey)) {
            throw new InvalidCallbackException('privateKey must be set to validate a callback');
        }

        $checksum = hash_hmac('sha256', $requestBody, $this->privateKey);

        return $checksum === $_SERVER['HTTP_QUICKPAY_CHECKSUM_SHA256'];
    }
}
