<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Payment;
use Kameli\Quickpay\Entities\PaymentLink;

class Payments extends Service
{
    /**
     * Get all payments
     * @return array
     */
    public function all()
    {
        return $this->createCollection($this->client->request('GET', '/payments'), Payment::class);
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
     * Create a payment
     * Required parameters: currency, order_id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function create($parameters)
    {
        return new Payment($this->client->request('POST', '/payments', $parameters));
    }

    /**
     * Authorize a payment
     * @param int $id
     * @param string $cardToken
     * @param int $amount
     * @param bool $autoFee
     * @return object mixed
     */
    public function authorize($id, $cardToken, $amount, $autoFee = true)
    {
        return new Payment($this->client->request('POST', "/payments/{$id}/authorize?synchronized", [
            'amount' => $amount,
            'card[token]' => $cardToken,
            'autofee' => $autoFee,
        ]));
    }

    /**
     * Capture a payment
     * @param int $id
     * @param int $amount
     * @return \Kameli\Quickpay\Entities\Payment
     */
    public function capture($id, $amount)
    {
        return new Payment(
            $this->client->request('POST', "/payments/{$id}/capture?synchronized", ['amount' => $amount])
        );
    }

    /**
     * Create/update a payment link
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\PaymentLink
     */
    public function link($id, $parameters)
    {
        return new PaymentLink($this->client->request('PUT', "/payments/{$id}/link", $parameters));
    }
}