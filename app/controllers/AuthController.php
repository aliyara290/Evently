<?php
namespace App\controllers;

use App\Core\View;

class AuthController{

    public function register(){
        view::render("register");
    }
}