<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Link;
use Kameli\Quickpay\Entities\Payout;

class Payouts extends Service
{
    /**
     * Get all payouts
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payout[]
     */
    public function all($parameters = [])
    {
        return $this->createCollection($this->client->request('GET', '/payouts', $parameters), Payout::class);
    }

    /**
     * Create a payout
     * Required parameters: currency, order_id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payout
     */
    public function create($parameters)
    {
        $parameters = $this->encodeVariables($parameters);

        return new Payout($this->client->request('POST', '/payouts', $parameters));
    }

    /**
     * Create/update a payout link
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Link
     */
    public function link($id, $parameters)
    {
        return new Link($this->client->request('PUT', "/payouts/{$id}/link", $parameters));
    }

    /**
     * Delete a payout link
     * @param int $id
     */
    public function deleteLink($id)
    {
        $this->client->request('DELETE', "/payouts/{$id}/link");
    }

    /**
     * Get payout
     * @param int $id
     * @return \Kameli\Quickpay\Entities\Payout
     */
    public function get($id)
    {
        return new Payout($this->client->request('GET', "/payouts/{$id}"));
    }

    /**
     * Update a payout
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payout
     */
    public function update($id, $parameters)
    {
        $parameters = $this->encodeVariables($parameters);

        return new Payout($this->client->request('PATCH', "/payouts/{$id}", $parameters));
    }

    /**
     * Credit a payout
     * Required parameters: amount
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Payout
     */
    public function credit($id, $parameters)
    {
        return new Payout($this->client->request('POST', "/payouts/{$id}/credit?synchronized", $parameters));
    }
}
