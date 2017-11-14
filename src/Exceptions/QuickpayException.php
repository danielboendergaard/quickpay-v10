<?php

namespace Kameli\Quickpay\Exceptions;

class QuickpayException extends \Exception
{
    /** @var string */
    protected $response;

    public function __construct($message, $response, $statusCode)
    {
        $this->response = $response;
        parent::__construct($message, $statusCode);
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}
