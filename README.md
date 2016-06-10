# Quickpay V10

####Features:
* Access the Quickpay API
	- Manage Payments
	- Manage Subscriptions
	- Get and retry failed callbacks
* Geneate payment links
* Validate callbacks
* Generate payment forms

##Examples

### Process a payment via payment link (prefered method)

````php
<?php

use Kameli\Quickpay\Quickpay;

$qp = new Quickpay('API_KEY', 'PRIVATE_KEY');
$payment = $qp->payments()->create([
    'currency' => 'DKK',
    'order_id' => 'SOME_UNIQUE_ORDER_ID',
]);

$link = $qp->payments()->link($payment->getId(), [
    'amount' => 10000, // amount in least valuable unit (øre)
]);

// Make the user follow the payment link which will take them to a form where you put in their card details
$url = $link->getUrl();

// When the form has been completed, a POST request will be sent to a specified url where you will validate it
if ($qp->validateCallback()) {
    $payment = $qp->receiveCallback();
    // Handle order
}
````
