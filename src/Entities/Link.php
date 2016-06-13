<?php

namespace Kameli\Quickpay\Entities;

class Link extends Entity
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
