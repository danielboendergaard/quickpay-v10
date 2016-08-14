<?php

namespace Kameli\Quickpay;

use InvalidArgumentException;

class Form
{
    const FORM_ACTION = 'https://payment.quickpay.net';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var array
     */
    protected $parameters = [
        'version' => 'v10',
    ];

    /**
     * @var array
     */
    protected static $requiredParameters = [
        'version', 'merchant_id', 'agreement_id', 'order_id', 'amount', 'currency', 'continueurl', 'cancelurl',
    ];

    /**
     * @param int $merchantId
     * @param int $agreementId
     * @param string $apiKey
     * @param string $privateKey
     */
    public function __construct($merchantId, $agreementId, $apiKey, $privateKey)
    {
        $this->parameters = array_merge($this->parameters, [
            'merchant_id' => $merchantId,
            'agreement_id' => $agreementId,
        ]);

        $this->apiKey = $apiKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function action()
    {
        return static::FORM_ACTION;
    }

    /**
     * @param array $parameters
     */
    public function parameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * @param string|array $name
     * @param mixed $value
     */
    public function variable($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $variableName => $value) {
                $this->variable($variableName, $value);
            }
        } else {
            $this->parameters["variables[{$name}]"] = $value;
        }
    }

    /**
     * Render the form
     * @return string
     */
    public function render()
    {
        $missingParameters = array_diff(static::$requiredParameters, array_keys($this->parameters));

        if (! empty($missingParameters)) {
            $message = 'Missing arguments for Quickpay Form: ' . implode(', ', $missingParameters);
            throw new InvalidArgumentException($message);
        }

        $parameters = array_merge($this->parameters, ['checksum' => $this->checksum()]);
        $fields = array_map(function ($name, $value) {
            return sprintf('<input type="hidden" name="%s" value="%s">', $name, $value);
        }, array_keys($parameters), $parameters);

        return implode("\n", $fields);
    }

    protected function checksum()
    {
        ksort($this->parameters);

        return hash_hmac('sha256', implode(' ', $this->parameters), $this->apiKey);
    }

    /**
     * @param string $requestBody
     * @return bool
     */
    public function validateCallback($requestBody = null)
    {
        $requestBody = $requestBody ?: file_get_contents('php://input');
        $checksum = hash_hmac('sha256', $requestBody, $this->privateKey);

        return $checksum === $_SERVER['HTTP_QUICKPAY_CHECKSUM_SHA256'];
    }
}
