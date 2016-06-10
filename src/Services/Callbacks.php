<?php

namespace Kameli\Quickpay\Services;

use Kameli\Quickpay\Entities\Callback;

class Callbacks extends Service
{
    /**
     * Get failed and queued callbacks
     * @return \Kameli\Quickpay\Entities\Callback[]
     */
    public function all()
    {
        return $this->createCollection($this->client->request('GET', '/callbacks'), Callback::class);
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
