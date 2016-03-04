<?php
namespace Kameli\Quickpay;

use Symfony\Component\HttpFoundation\Request;
use UnexpectedValueException;

class CallbackRequest
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $privateKey
     */
    public function __construct(Request $request, $privateKey = null)
    {
        $this->request = $request;
        $this->privateKey = $privateKey;
        $this->parameters = json_decode($request->getContent(), true);
    }

    /**
     * Check if a payment has been authorized
     * @return bool
     */
    public function authorized()
    {
        return $this->valid() && $this->get('accepted') && ! $this->get('test_mode');
    }

    /**
     * Check if a test payment has been authorized
     * @return bool
     */
    public function authorizedTest()
    {
        return $this->valid() && $this->get('accepted') && $this->get('test_mode');
    }

    /**
     * Validate a callback request
     * @return bool
     */
    public function valid()
    {
        if (! isset($this->privateKey)) {
            throw new UnexpectedValueException('privateKey must be set to validate a callback');
        }

        $input = file_get_contents('php://input');

        $checksum = hash_hmac('sha256', $input, $this->privateKey);

        return $checksum === $this->request->headers->get('QuickPay-Checksum-Sha256');
    }

    /**
     * Get the request as an array
     * @return array
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * Get a specific parameter
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    /**
     * Get a specific variable
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function variable($name, $default = null)
    {
        return isset($this->parameters['variables'][$name]) ? $this->parameters['variables'][$name] : $default;
    }

    /**
     * Get the authorized amount
     * @return float
     */
    public function amount()
    {
        foreach ($this->get('operations') as $operation) {
            if ($operation['type'] == 'authorize') {
                return $operation['amount'] / 100;
            }
        }

        return null;
    }

}