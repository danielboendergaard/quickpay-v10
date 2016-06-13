<?php
namespace Kameli\Quickpay\Entities;

abstract class Entity
{
    /**
     * @param array|object $data
     */
    public function __construct($data)
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }
}
