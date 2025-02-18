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

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->ticketData = new Ticket();
    }
    public function seccussPayment() {
        View::render("event/tecket", ["ticket" => $this->ticketData()]);
    }

    public function ticketData() {
        return $this->ticketData->getTicketById(Session::get("ticketId"));
    }
}
