<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Callback;

class Callbacks extends Service
{
    /**
     * Get failed and queued callbacks
     * @param array $parameters
     * @return \Kameli\Quickpay\Entities\Callback[]
     */
    public function all($parameters = null)
    {
        return $this->createCollection($this->client->request('GET', '/callbacks', $parameters), Callback::class);
    }

    /**
     * Retry failed callback
     * @param int $id
     * @return \Kameli\Quickpay\Entities\Callback
     */
    public function retry($id)
    {
        return new Callback($this->client->request('PATCH', "/callbacks/{$id}/retry"));
    }
}
