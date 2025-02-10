<?php
namespace App\Controllers\Front;
use App\Core\View;

class AdminController {
    public function Category_page() {
        View::render("admin/Category");
    }
    public function Mange_user_page() {
        View::render("admin/Mange_user");
    }
}