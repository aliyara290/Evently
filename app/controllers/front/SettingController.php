<?php 
namespace App\Controllers\Front;
use App\Core\View;

class SettingController {
    public function profile() {
        View::render("setting/profile");
    }
    public function setting() {
        View::render("setting/setting");
    }
    
}