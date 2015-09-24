<?php
namespace Kameli\Quickpay;

class Form
{
    const FORM_ACTION = 'https://payment.quickpay.net';

    public function __construct($parameters)
    {

    }

    /**
     * Render the form
     * @return string
     */
    public function render()
    {
        return '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}