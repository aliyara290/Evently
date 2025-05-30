<?php 
namespace App\Controllers\Front;
use App\Core\View;
use App\Core\Session;


class UserProfileController {
    private $userData;

    public function __construct() {
        $this->userData = Session::get("user");
    }
    public function page() {
        View::render("profile/profile", ["user" => $this->userData]);
    }
}