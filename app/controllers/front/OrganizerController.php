<?php 
namespace App\Controllers\Front;
use App\Core\View;

class OrganizerController {
    public function page() {
        View::render("organizer/dashboard");
    }
  

    public function Event_management_page() {
        View::render("organizer/Event_management");
    }
}