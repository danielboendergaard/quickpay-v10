<?php

namespace Kameli\Quickpay\Exceptions;

use Exception;

class ValidationException extends Exception
{
    /**
     * ValidationException constructor.
     * @param array $errors
     */
    public function __construct($errors)
    {
        parent::__construct(json_encode($errors));
    }

}
