<?php

namespace App\Controllers\back;

use App\Core\View;
use App\Core\Session;
use App\Models\Ticket;

class TicketController
{
    private $userData;
    private $ticketData;

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->ticketData = new Ticket();
    }

    public function bookTicket()
    {
        $this->ticketData->setEventId(23);
        $this->ticketData->setUserId(12);
        $this->ticketData->setPrice(11);
        $this->ticketData->setStatus('pending');

        $events = $this->ticketData->createTicket();
        $this->ticketData->decrementAvailableTickets(23);
        View::render("buy");

    }


}