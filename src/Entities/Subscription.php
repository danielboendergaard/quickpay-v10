<?php

namespace Kameli\Quickpay\Entities;

use Kameli\Quickpay\Traits\Variables;

/**
 * @property int id
 * @property int merchant_id
 * @property string order_id
 * @property bool accepted
 * @property string type
 * @property string text_on_statement
 * @property int branding_id
 * @property array variables
 * @property string currency
 * @property string state
 * @property object metadata
 * @property object link
 * @property string shipping_address
 * @property string invoice_address
 * @property array basket
 * @property array operations
 * @property bool test_mode
 * @property string acquirer
 * @property string facilitator
 * @property string created_at
 * @property string description
 */
class Subscription extends Entity
{
    use Variables;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
