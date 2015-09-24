<?php
namespace Kameli\Quickpay;

use InvalidArgumentException;

class Form
{
    const FORM_ACTION = 'https://payment.quickpay.net';

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
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * @return string
     */
    public function action()
    {
        return static::FORM_ACTION;
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

        $fields = [];
        foreach ($this->parameters as $parameter => $value) {
            $fields[] = sprintf('<input type="hidden" name="%s" value="%s">', $parameter, $value);
        }

        return implode("\n", $fields);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}