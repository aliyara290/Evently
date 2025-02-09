<?php 
namespace App\Controllers\Front;
use App\Core\View;

class EventController {
    public function page() {
        View::render("event/page");
    }
}