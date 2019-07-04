<?php

namespace Kameli\Quickpay\Entities;

use Kameli\Quickpay\Traits\Variables;

/**
 * @property int $id
 * @property int $merchant_id
 * @property bool $accepted
 * @property array $operations
 * @property object $metadata
 * @property object $link
 * @property bool $test_mode
 * @property string $acquirer
 * @property string $type
 * @property string $created_at
 */
class Card extends Entity
{
    use Variables;
}
