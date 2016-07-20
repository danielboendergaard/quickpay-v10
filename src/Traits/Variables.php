<?php

namespace Kameli\Quickpay\Traits;

trait Variables
{
    /** @var object */
    protected $variables;

    /**
     * Get a specific variable
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function variable($name, $default = null)
    {
        return isset($this->variables->{$name}) ? json_decode($this->variables->{$name}) : $default;
    }

    /**
     * Get all variables
     * @return object
     */
    public function variables()
    {
        return (object) array_map('json_decode', (array) $this->variables);
    }
}
