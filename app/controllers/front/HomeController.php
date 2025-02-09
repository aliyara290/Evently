<?php 
namespace App\Controllers\Front;
use App\Core\View;

class HomeController {
    public function home() {
        View::render("home", ["user" => "Ali Yara"]);
    }
}