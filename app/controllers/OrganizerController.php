<?php 
namespace App\Controllers;
use App\Core\View;

class OrganizerController {
    public function page() {
        View::render("admin/dashboard");
    }
    public function Category_page() {
        View::render("admin/Category");
    }
    public function Mange_user_page() {
        View::render("admin/Mange_user");
    }
}  