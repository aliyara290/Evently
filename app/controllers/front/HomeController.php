<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Core\Session;
use App\Models\Event;

class HomeController {
    private $userData;
    private $eventData;

    public function __construct() {
        $this->userData = Session::get("user");
        $this->eventData= new Event();
    }
    public function home() {
        $result=$this->eventData->getlastEightEvents();
//        var_dump($this->userData);
        View::render("home", ["user" => $this->userData,"records" => $result]);
    }
}