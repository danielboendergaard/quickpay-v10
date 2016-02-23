<?php
namespace Kameli\Quickpay;

use Exception;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * ValidationException constructor.
     * @param array $errors
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($errors, $message, $code, $previous)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

}