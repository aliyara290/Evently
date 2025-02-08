<?php 
namespace App\Controllers;
use App\Core\View;

class OrganizerController {
    public function page() {
        View::render("organizer/dashboard");
    }
    public function Category_page() {
        View::render("organizer/Category");
    }
    public function Mange_user_page() {
        View::render("organizer/Mange_user");
    }
    public function Event_management_page() {
        View::render("organizer/Event_management");
    }
}  