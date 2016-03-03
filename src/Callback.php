<?php

namespace Kameli\Quickpay;

use Symfony\Component\HttpFoundation\Request;
use UnexpectedValueException;

class Callback
{
    /**
     * @var \Kameli\Quickpay\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @param \Kameli\Quickpay\Client $client
     * @param string $privateKey
     */
    public function __construct(Client $client, $privateKey = null)
    {
        $this->client = $client;
        $this->privateKey = $privateKey;
    }

    /**
     * Get failed and queued callbacks
     * @return array
     */
    public function all()
    {
        return $this->client->request('GET', '/callbacks');
    }

    /**
     * Retry failed callback
     * @param int $id
     * @return object
     */
    public function retry($id)
    {
        return $this->client->request('PATCH', "/callbacks/{$id}/retry");
    }

    /**
     * Validate a callback request
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function validate(Request $request)
    {
        if (! isset($this->privateKey)) {
            throw new UnexpectedValueException('privateKey must be set to validate a callback');
        }

        $input = file_get_contents('php://input');

        $checksum = hash_hmac('sha256', $input, $this->privateKey);

        return $checksum === $request->headers->get('QuickPay-Checksum-Sha256');
    }
}