<?php 
namespace App\Controllers;
use App\Core\View;

class HomeController {
    public function home() {
        View::render("home", ["user" => "Ali Yara"]);
    }
}