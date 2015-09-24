<?php
namespace Kameli\Quickpay;

class Form
{
    const FORM_ACTION = 'https://payment.quickpay.net';

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
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