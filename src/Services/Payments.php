<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Entities\Link;

class Payments extends Service
{
    /**
     * Get all payments
     * @return \Kameli\Quickpay\Entities\Payment[]
     */
    public function all()
    {
        return $this->createCollection($this->client->request('GET', '/payments'), Payment::class);
    }

    /**
     * Create a payment
     * Required parameters: currency, order_id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function create($parameters)
    {
        $parameters = $this->encodeVariables($parameters);
        return new Payment($this->client->request('POST', '/payments', $parameters));
    }

    /**
     * Create/update a payment link
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Link
     */
    public function link($id, $parameters)
    {
        return new Link($this->client->request('PUT', "/payments/{$id}/link", $parameters));
    }

    /**
     * Get payment
     * @param int $id
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function get($id)
    {
        return new Payment($this->client->request('GET', "/payments/{$id}"));
    }

    /**
     * Update a payment
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function update($id, $parameters)
    {
        $parameters = $this->encodeVariables($parameters);
        return new Payment($this->client->request('PATCH', "/payments/{$id}", $parameters));
    }

    /**
     * Create a payment session
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function session($id, $parameters)
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/session?synchronized", $parameters));
    }

    /**
     * Authorize a payment
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function authorize($id, $parameters)
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/authorize?synchronized", $parameters));
    }

    /**
     * Capture a payment
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function capture($id, $parameters)
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/capture?synchronized", $parameters));
    }

    /**
     * Capture a payment with specified amount
     * @param int $id
     * @param int $amount
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function captureAmount($id, $amount)
    {
        return $this->capture($id, ['amount' => $amount]);
    }

    /**
     * Capture a payment's entire amount
     * @param \Kameli\Quickpay\Entities\Payment $payment
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function capturePayment(Payment $payment)
    {
        return $this->captureAmount($payment->getId(), $payment->amount());
    }

    /**
     * Refund a payment
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function refund($id, $parameters)
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/refund?synchronized", $parameters));
    }

    /**
     * Refund a payment with specified amount
     * @param int $id
     * @param int $amount
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function refundAmount($id, $amount)
    {
        return $this->refund($id, ['amount' => $amount]);
    }

    /**
     * Cancel a payment
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function cancel($id, $parameters = [])
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/cancel?synchronized", $parameters));
    }

    /**
     * Renew a payment
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function renew($id, $parameters = [])
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/renew?synchronized", $parameters));
    }
}
