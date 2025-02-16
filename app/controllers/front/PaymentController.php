<?php

namespace App\Controllers\Front;

use App\Models\StripePayment;


class PaymentController
{
    private $stripModel;
    private $paypalModel;
    private $eventId;

    public function __construct()
    {
        $this->stripModel = new StripePayment($_SESSION["user"]["id"], 1, $this->eventId);
        // $this->stripModel = ;
    }

    // Handle Stripe payment creation
    public function createStripePayment()
    {
        $amount = $_POST['amount']; // Amount from form submission
        $this->eventId = $_POST['event_id']; // Event ID from form submission
        $currency = 'USD'; // Currency (adjust if needed)
        $ticket = $this->stripModel->insertTicket($amount);
        if ($ticket) {
            
        } else {
            echo "failed to insert ticket";
        }
        // Create Stripe Checkout Session
        $checkoutSessionResponse = $this->stripModel->createCheckoutSession($amount, $currency);

        if ($checkoutSessionResponse['status'] == 'success') {
            // Redirect the user to the Stripe Checkout page
            header('Location: ' . $checkoutSessionResponse['checkout_url']);
            exit();
        } else {
            // Handle error (e.g., unable to create the checkout session)
            echo "Error: " . $checkoutSessionResponse['message'];
        }
    }

    // Handle PayPal payment creation
    // public function createPayPalPayment() {
    //     $userId = $_SESSION['user_id'];
    //     $ticketId = $_SESSION['ticket_id'];
    //     $amount = $_POST['amount'];
    //     $currency = 'USD';

    //     $payment = new PayPalPayment($this->db, $userId, $ticketId);
    //     $paymentIntent = $payment->createPaymentIntent($amount, $currency);

    //     if (isset($paymentIntent['id'])) {
    //         header('Location: ' . $paymentIntent['links'][1]['href']);
    //     } else {
    //         echo "Error: " . $paymentIntent['message'];
    //     }
    // }

    // Confirm payment after redirect from Stripe or PayPal
    public function confirmPayment()
    {
        if (!isset($_GET['session_id'])) {
            die("Invalid request: No session ID found.");
        }

        $sessionId = $_GET['session_id'];
        $this->stripModel->confirmation($sessionId);
    }
}
