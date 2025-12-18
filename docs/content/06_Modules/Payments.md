Payment Base Controller

The PaymentController is an abstract base class intended to be extended by specific payment gateway integrations (e.g., Stripe, PayPal).

Implementing a Gateway

Create a class that extends PaymentController.
PHP

class StripeController extends PaymentController
{
public function initialize($transaction_code) {
// Setup Stripe API
}

    public function frontend_form() {
        // Return HTML for credit card form
    }
}

Configuration

The controller handles sandbox vs live mode automatically based on the gateway configuration passed to config().
Property	Description
$mode	'live' or 'sandbox'
$config	The JSON object containing API keys/secrets.
$urls	Array defining endpoints for live/sandbox.
$needs_to_confirm	Boolean. If true, payment isn't instant (e.g., Bank Transfer).

Callbacks

The framework expects standard methods for handling IPNs/Webhooks:

    callback($transaction): Handled automatically by the system.

    confirm($transaction): Used to manually approve a payment.

Shipping.md

Shipping Base Controller

The ShippingController provides the standard interface for shipping providers (FedEx, DHL, Aramex).

Key Methods to Implement

Method	Purpose
createShipment($details)	Sends order data to the API and generates a tracking number.
printLabel($details)	Returns the PDF or image URL of the shipping label.
returnShipment($details)	Generates a return ticket.

Parameter Handling

Use setParam and getParam to pass temporary data between the checkout process and the shipping API logic.
PHP

$this->setParam('weight', 5.2);
$this->setParam('dimensions', [10, 10, 5]);