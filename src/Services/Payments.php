<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Entities\Link;
use Kameli\Quickpay\Exceptions\NotFoundException;

class Payments extends Service
{
    /**
     * Get all payments
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment[]
     */
    public function all($parameters = null)
    {
        return $this->createCollection($this->client->request('GET', '/payments', $parameters), Payment::class);
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
     * Get a payment by the order id
     * @param string $orderId
     * @return \Kameli\Quickpay\Entities\Payment
     * @throws \Kameli\Quickpay\Exceptions\NotFoundException
     */
    public function getByOrderId($orderId)
    {
        $payments = $this->all(['order_id' => $orderId]);

        if (! count($payments)) {
            throw new NotFoundException("No payment exists with order id {$orderId}");
        }

        return $payments[0];
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
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function session($id, $parameters, $async = false)
    {
        $query = $async ? '' : '?synchronized';

        return new Payment($this->client->request('POST', "/payments/{$id}/session{$query}", $parameters));
    }

    /**
     * Authorize a payment
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function authorize($id, $parameters, $async = false)
    {
        $query = $async ? '' : '?synchronized';

        return new Payment($this->client->request('POST', "/payments/{$id}/authorize{$query}", $parameters));
    }

    /**
     * Capture a payment
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function capture($id, $parameters, $async = false)
    {
        $query = $async ? '' : '?synchronized';

        return new Payment($this->client->request('POST', "/payments/{$id}/capture{$query}", $parameters));
    }

    /**
     * Capture a payment with specified amount
     * @param int $id
     * @param int $amount
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function captureAmount($id, $amount, $async = false)
    {
        return $this->capture($id, ['amount' => $amount], $async);
    }

    /**
     * Capture a payment's entire amount
     * @param \Kameli\Quickpay\Entities\Payment $payment
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function capturePayment(Payment $payment, $async = false)
    {
        return $this->captureAmount($payment->getId(), $payment->amount(), $async);
    }

    /**
     * Refund a payment
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function refund($id, $parameters, $async = false)
    {
        $query = $async ? '' : '?synchronized';

        return new Payment($this->client->request('POST', "/payments/{$id}/refund{$query}", $parameters));
    }

    /**
     * Refund a payment with specified amount
     * @param int $id
     * @param int $amount
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function refundAmount($id, $amount, $async = false)
    {
        return $this->refund($id, ['amount' => $amount], $async);
    }

    /**
     * Cancel a payment
     * @param int $id
     * @param array $parameters
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function cancel($id, $parameters = [], $async = false)
    {
        $query = $async ? '' : '?synchronized';

        return new Payment($this->client->request('POST', "/payments/{$id}/cancel{$query}", $parameters));
    }

    /**
     * Renew a payment
     * @param int $id
     * @param array $parameters
     * @param bool $async
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function renew($id, $parameters = [], $async = false)
    {
        $query = $async ? '' : '?synchronized';

        return new Payment($this->client->request('POST', "/payments/{$id}/renew{$query}", $parameters));
    }
}
