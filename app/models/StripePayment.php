<?php

namespace App\Models;

use App\Models\Payment;
use Exception;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Stripe\Checkout\Session;

class StripePayment extends Payment
{
    private $stripeSecretKey;

    public function __construct($userId, $ticketId, $eventId)
    {
        parent::__construct($userId, $ticketId, $eventId);
        $this->stripeSecretKey = $_ENV["STRIPE_SECRET_KEY"];
    }

    public function createCheckoutSession($amount, $currency)
    {
        Stripe::setApiKey($this->stripeSecretKey);

        try {
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => $currency,
                            'product_data' => [
                                'name' => 'Event Ticket',
                            ],
                            'unit_amount' => $amount * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment', 
                'success_url' => 'http://localhost:8080/payment/confirm?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'http://localhost:8080/payment/cancel',
            ]);

            return [
                'status' => 'success',
                'session_id' => $checkoutSession->id,
                'checkout_url' => $checkoutSession->url, 
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function confirmation($sessionId) {
        try {
            Stripe::setApiKey($this->stripeSecretKey);
    
            // Retrieve session details from Stripe
            $session = Session::retrieve($sessionId);
    
            if ($session->payment_status == 'paid') {
                // Save payment details in the database
                $this->savePayment(
                    "stripe",
                    $session->id, // Use session_id as transaction ID
                    $session->amount_total / 100,
                    $session->currency,
                    "completed"
                );
                header("Location: /payment/success");
                exit();
            } else {
                header("Location: /payment/failed");
                exit();
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            die("Stripe API error: " . $e->getMessage());
        }
    }

    public function processPayment($paymentData)
    {
        return $this->savePayment('stripe', $paymentData['payment_id'], $paymentData['amount'], $paymentData['currency'], 'completed');
    }
}
