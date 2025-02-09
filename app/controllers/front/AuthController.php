<?php
namespace App\Controllers\Front;

use App\Core\View;
// client-id: 523106584100-hrtf77h34cd7hfsau27vislklbu7mfea.apps.googleusercontent.com
// clinet-secret: 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
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
