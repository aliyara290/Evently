<?php 
namespace App\Controllers\Front;
use App\Core\View;

class EventsController {
    public function page() {
        View::render("events");
    }
}