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
        $this->setMessage($message, $errors);
        $this->errors = $errors;
        $this->errorCode = $errorCode;
    }

    /**
     * Set the exception message
     * @param string $message
     * @param array $errors
     */
    public function setMessage($message, $errors)
    {
        $this->message = $message . ': ' . implode(', ', array_map(function ($error, $attribute) {
            return $attribute . ': ' . implode(', ', $error);
        }, $errors, array_keys($errors)));
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
