<?php

namespace App\Controllers\back;

use App\Controllers\Mail\MailController;
use App\Core\View;
use App\Core\Session;
use App\Models\Ticket;

class TicketController
{
    private $userData;
    private $ticketData;

    private $mail;

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->ticketData = new Ticket();
        $this->mail=new MailController();
    }

    public function bookFree()
    {
        $this->ticketData->setEventId($_GET['id']);
//        var_dump($_GET['id']);
        $this->ticketData->setUserId($this->userData['id']);
        $this->ticketData->setPrice(0);
        $this->ticketData->setStatus('approved');


         $this->ticketData->createTicket();
        $this->ticketData->decrementAvailableTickets($_GET['id']);
        $this->mail->sendApprovedMail();

    }


}