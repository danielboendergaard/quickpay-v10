<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Entities\Link;
use Kameli\Quickpay\Entities\Subscription;

class Subscriptions extends Service
{
    /**
     * Get all subscriptions
     * @return \Kameli\Quickpay\Entities\Subscription[]
     */
    public function all()
    {
        return $this->createCollection($this->client->request('GET', '/subscriptions'), Subscription::class);
    }

    /**
     * Create a subscription
     * Required parameters: order_id, currency, description
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function create($parameters)
    {
        $parameters = $this->encodeVariables($parameters);
        return new Subscription($this->client->request('POST', '/subscriptions', $parameters));
    }

    /**
     * Create/update a subscription link
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Link
     */
    public function link($id, $parameters)
    {
        return new Link($this->client->request('PUT', "/subscriptions/{$id}/link", $parameters));
    }

    /**
    * Get subscription
    * @param int $id
    * @return \Kameli\Quickpay\Entities\Subscription
    */
    public function get($id)
    {
        return new Subscription($this->client->request('GET', "/subscriptions/{$id}"));
    }

    /**
     * Update a subscription
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function update($id, $parameters)
    {
        $parameters = $this->encodeVariables($parameters);
        return new Subscription($this->client->request('PATCH', "/subscriptions/{$id}", $parameters));
    }

    /**
     * Create a subscription session
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function session($id, $parameters)
    {
        return new Subscription(
            $this->client->request('POST', "/subscriptions/{$id}/session?synchronized", $parameters)
        );
    }

    /**
     * Authorize a subscription
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function authorize($id, $parameters)
    {
        return new Subscription(
            $this->client->request('POST', "/subscriptions/{$id}/authorize?synchronized", $parameters)
        );
    }

    /**
     * Cancel a subscription
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Subscription
     */
    public function cancel($id, $parameters = [])
    {
        return new Subscription(
            $this->client->request('POST', "/subscriptions/{$id}/cancel?synchronized", $parameters)
        );
    }

    /**
     * Create a subscription recurring payment
     * Required parameters: amount, order_id
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function recurring($id, $parameters)
    {
        return new Payment($this->client->request('POST', "/subscriptions/{$id}/recurring?synchronized", $parameters));
    }

    /**
     * Get all subscription payments
     * @param int $id
     * @return \Kameli\Quickpay\Entities\Payment[]
     */
    public function payments($id)
    {
        return $this->createCollection($this->client->request('GET', "/subscriptions/{$id}/payments"), Payment::class);
    }
}
