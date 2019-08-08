<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Card;
use Kameli\Quickpay\Entities\CardToken;
use Kameli\Quickpay\Entities\FraudReport;
use Kameli\Quickpay\Entities\Link;

class Cards extends Service
{
    /**
     * Get all saved cards
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Card[]
     */
    public function all($parameters = null)
    {
        return $this->createCollection($this->client->request('GET', '/cards', $parameters), Card::class);
    }

    /**
     * Create saved card
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Card
     */
    public function create($parameters = [])
    {
        $parameters = $this->encodeVariables($parameters);
        return new Card($this->client->request('POST', '/cards', $parameters));
    }

    /**
     * Get saved card
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Card
     */
    public function get($id, $parameters = [])
    {
        return new Card($this->client->request('GET', "/cards/{$id}", $parameters));
    }

    /**
     * Update saved card
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Card
     */
    public function update($id, $parameters)
    {
        $parameters = $this->encodeVariables($parameters);
        return new Card($this->client->request('PATCH', "/cards/{$id}", $parameters));
    }

    /**
     * Authorize saved card
     * Required parameters: card[number], card[expiration]
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Card
     */
    public function authorize($id, $parameters)
    {
        return new Card($this->client->request('POST', "/cards/{$id}/authorize?synchronized", $parameters));
    }

    /**
     * Cancel saved card
     * @param int $id
     * @return \Kameli\Quickpay\Entities\Card
     */
    public function cancel($id)
    {
        return new Card($this->client->request('POST', "/cards/{$id}/cancel?synchronized"));
    }

    /**
     * Create card token
     * @return \Kameli\Quickpay\Entities\CardToken
     */
    public function createToken($id)
    {
        return new CardToken($this->client->request('POST', "/cards/{$id}/tokens"));
    }

    /**
     * Create/update a payment link
     * @param int $id
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Link
     */
    public function link($id, $parameters = [])
    {
        return new Link($this->client->request('PUT', "/cards/{$id}/link", $parameters));
    }

    /**
     * Delete a branding
     * @param int $id
     */
    public function deleteLink($id)
    {
        $this->client->request('DELETE', "/cards/{$id}/link");
    }

    /**
     * Create fraud confirmation report
     * @param int $id
     * @return \Kameli\Quickpay\Entities\FraudReport
     */
    public function createFraudReport($id)
    {
        return new FraudReport($this->client->request('POST', "/cards/{$id}/fraud-report"));
    }
}
