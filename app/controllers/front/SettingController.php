<?php 
namespace App\Controllers\Front;
use App\Core\View;
use App\Core\Session;

class SettingController {
    private $userData;

    public function __construct() {
        $this->userData = Session::get("user");
    }
    public function profile() {
        View::render("setting/profile", ["user" => $this->userData]);
    }
    public function setting() {
        View::render("setting/setting", ["user" => $this->userData]);
    }
    
}