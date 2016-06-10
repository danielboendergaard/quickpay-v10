<?php

namespace Kameli\Quickpay\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
