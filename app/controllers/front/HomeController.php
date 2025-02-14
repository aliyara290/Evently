<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Core\Session;

class HomeController {
    private $userData;

    public function __construct() {
        $this->userData = Session::get("user");
    }
    public function home() {
        View::render("home", ["user" => $this->userData]);
    }
}