# Quickpay V10

#### Features:
* Access the Quickpay API
	- Manage Payments
	- Manage Subscriptions
	- Get and retry failed callbacks
* Geneate payment links
* Validate callbacks
* Generate payment forms

Go to the official QuickPay documentation to explore all endpoints and options: http://tech.quickpay.net/api/services/?scope=merchant

## Installation
`composer require kameli/quickpay-v10`

## Examples

### Process a payment

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

// Make the user follow the payment link which will take them to a form where they put in their card details
$url = $link->getUrl();

// When the form has been completed, a POST request will be sent to a specified url where you can validate it
if ($qp->validateCallback()) {
    $payment = $qp->receivePaymentCallback();

    // Capture the amount to charge the card
    $qp->payments()->captureAmount($payment->getId(), $payment->amount());

    // Handle order
}
````

### Create a subscription and make recurring payments

*Remember to enable subscriptions for the acquirer you are using.*

````php
<?php

use Kameli\Quickpay\Quickpay;

$qp = new Quickpay('API_KEY', 'PRIVATE_KEY');
$subscription = $qp->subscriptions()->create([
    'currency' => 'DKK',
    'order_id' => 'SOME_UNIQUE_ORDER_ID',
    'description' => 'Abonnement',
]);

$link = $qp->subscriptions()->link($subscription->getId(), [
    'amount' => 100, // the amount does not matter here, but is still required for some reason
]);

// Make the user follow the payment link which will take them to a form where they put in their card details
$url = $link->getUrl();

// When the form has been completed, a POST request will be sent to a specified url where you can validate it
if ($qp->validateCallback()) {
    $subscription = $qp->receiveSubscriptionCallback();
}

// Use the recurring method to make new payments
$payment = $qp->subscriptions()->recurring($subscription->getId(), [
    'amount' => 10000,
    'order_id' => 'SOME_UNIQUE_ORDER_ID',
]);

// Capture the amount to charge the card
$qp->payments()->captureAmount($payment->getId(), $payment->amount());
````
