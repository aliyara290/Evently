<?php 
namespace App\Controllers\Front;
use App\Core\View;

class HomeController {
    public function home() {
        View::render("home", [
            "firstName" => $_SESSION["firstName"],
            "lastName" => $_SESSION["lastName"],
            "avatar" => $_SESSION["avatar"],
            "id" => $_SESSION["id"],
            "email" => $_SESSION["email"],
        ]);
    }
}