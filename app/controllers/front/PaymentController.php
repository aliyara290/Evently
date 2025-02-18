<?php

namespace App\Controllers\Front;

use App\Models\StripePayment;
use App\Models\Ticket;
use App\Core\Session;
use App\Controllers\Mail\MailController;

class PaymentController
{
    private $stripModel;
    private $ticketModel;
    private $mail;
    // private int $places = 0;

    public function __construct()
    {
        $this->stripModel = new StripePayment($_SESSION["user"]["id"]);
        $this->ticketModel = new Ticket();
        $this->mail = new MailController();
    }

    public function createStripePayment()
    {
        $amount = $_POST['prix'];
        $total = $_POST['total'];
        Session::set("places", $_POST['nombre']);
        $event_name = $_POST['event_name'];
        Session::set("eventId", $_POST['event_id']);
        $currency = 'mad';
        $this->ticketModel->setEventId(Session::get("eventId"));
        $this->ticketModel->setUserId($_SESSION["user"]["id"]);
        $ticketId = $this->ticketModel->insertTicket($amount, Session::get("places"), $total);
        if ($ticketId) {
            Session::set("ticketId", $ticketId);
        } else {
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

    public function confirmPayment()
    {
        if (!isset($_GET['session_id'])) {
            die("Invalid request: No session ID found.");
        }

        $sessionId = $_GET['session_id'];
        $check = $this->stripModel->confirmation($sessionId);
        if ($check) {
            
            $isDescrement = $this->ticketModel->decrementAvailableTickets(Session::get("eventId"), Session::get("places"));
            if ($isDescrement) {
                echo "good";
            }
            $this->mail->sendApprovedMail();
            header("Location: /payment/success");
            exit();
        } else {
            header("Location: /payment/failed");
            exit();
        }
    }
}
