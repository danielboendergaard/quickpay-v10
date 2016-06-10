<?php
namespace Kameli\Quickpay\Entities;

abstract class Entity
{
    /**
     * @param array $data
     */
    public function __construct($data)
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }
}
