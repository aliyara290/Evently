<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Core\Session;
use App\Models\Event;

class EventsController
{
    private $userData;
    private $eventData;

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->eventData = new Event();
    }

    public function page()
    {
        $events = $this->eventData->readAllEvents();
        View::render("events", ["user" => $this->userData, "records" => $events]);

    }



}