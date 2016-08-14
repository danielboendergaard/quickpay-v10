<?php

namespace Kameli\Quickpay\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $errors;
    protected $errorCode;

    /**
     * ValidationException constructor.
     * @param string $message
     * @param array $errors
     * @param mixed $errorCode
     */
    public function __construct($message, $errors, $errorCode)
    {
        $this->message = $message;
        $this->errors = $errors;
        $this->errorCode = $errorCode;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
