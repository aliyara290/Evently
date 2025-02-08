<?php 
namespace App\Controllers;
use App\Core\View;

class EventController {
    public function page() {
        View::render("event/page");
    }
}