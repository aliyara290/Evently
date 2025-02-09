<?php
namespace App\Controllers\Front;

use App\Core\View;

class AuthController{

    public function register(){
        view::render("register");
    }

    public function login(){
        view::render("login");
    }
    public function forgetPassword(){
        view::render("forgetPassword");
    }

    public function resetPassword(){
        view::render("resetPassword");
    }
}
