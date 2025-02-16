<?php

namespace App\Controllers\Front;

use App\Models\StripePayment;
use App\Models\Ticket;
use App\Core\Session;

class PaymentController
{
    private $stripModel;
    private $ticketModel;
    private $paypalModel;
    private $eventId;
    private $ticketId;
    // private int $places = 0;

    public function __construct()
    {
        $this->stripModel = new StripePayment($_SESSION["user"]["id"], 1, $this->eventId);
        $this->ticketModel = new Ticket();
    }

    // Handle Stripe payment creation
    public function createStripePayment()
    {
        $amount = $_POST['prix'];
        Session::set("places", $_POST['nombre']);
        $event_name = $_POST['event_name'];
        Session::set("eventId", $_POST['event_id']);
        $currency = 'mad';
        $this->ticketModel->setEventId(Session::get("eventId"));
        $this->ticketModel->setUserId($_SESSION["user"]["id"]);
        $inserting = $this->ticketModel->insertTicket($amount);
        if (!$inserting) { 
            echo "failed to insert ticket";
            return false;
        }
        $checkoutSessionResponse = $this->stripModel->createCheckoutSession($amount, $currency, $event_name, Session::get("places"));

        if ($checkoutSessionResponse['status'] == 'success') {
            header('Location: ' . $checkoutSessionResponse['checkout_url']);
            exit();
        } else {
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
        $check = $this->stripModel->confirmation($sessionId);
        if ($check) {
            echo 'event:' . Session::get("eventId");
            echo 'plc:' . Session::get("places");
            $isDescrement = $this->ticketModel->decrementAvailableTickets(Session::get("eventId"), Session::get("places"));
            if ($isDescrement) {
                echo "good";
            }

            header("Location: /payment/success");
            exit();
        } else {
            header("Location: /payment/failed");
            exit();
        }
    }
}
