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
 * @property int balance
 */
class Payment extends Entity
{
    use Variables;

    /**
     * Check if a payment has been authorized
     * @return bool
     */
    public function authorized()
    {
        return $this->accepted && ! $this->test_mode;
    }

    /**
     * Check if a test payment has been authorized
     * @return bool
     */
    public function authorizedTest()
    {
        return $this->accepted && $this->test_mode;
    }

    /**
     * Get the authorized amount
     * @return float
     */
    public function amount()
    {
        foreach (array_reverse($this->operations) as $operation) {
            if (in_array($operation->type, ['authorize', 'recurring'])) {
                return $operation->amount;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
