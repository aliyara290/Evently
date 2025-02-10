<?php 
namespace App\Controllers\Front;
use App\Core\View;

class UserProfileController {
    public function page() {
        View::render("profile/profile");
    }
}