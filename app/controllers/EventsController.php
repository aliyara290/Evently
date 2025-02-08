<?php 
namespace App\Controllers;
use App\Core\View;

class EventsController {
    public function page() {
        View::render("events");
    }
}