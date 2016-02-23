<?php
namespace Kameli\Quickpay;

use Exception;

class ValidationException extends \Exception
{
    /**
     * ValidationException constructor.
     * @param array $errors
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($errors, $message, $code, $previous)
    {
        $message = json_encode($errors);
        parent::__construct($message, $code, $previous);
    }

}