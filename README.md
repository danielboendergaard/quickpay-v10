# Quickpay V10

### Not complete - Still under development

A few examples:
````php
<?php

use Kameli\Quickpay\Quickpay;

$qp = new Quickpay('API_KEY');

// Create a payment
$payment = $qp->payments->create([
	'currency' => 'DKK',
    'order_id' => 'ORDER_ID',
]);

// Authorize a payment from embedded form
$payment = $qp->payments()->authorize($payment->id, 'CARD_TOKEN', AMOUNT);

// Create a payment link
$link = $qp->payments()->link($payment->id, [
	'amount' => AMOUNT,
])

````
