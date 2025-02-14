<?php 
namespace App\Controllers\Front;
use App\Core\View;
use App\Core\Session;

class AppController {
    private $userData;

    public function __construct() {
        $this->userData = Session::get("user");
    }

    public function notFound() {
        View::render("404", ["user" => $this->userData]);
    }
    
    public function forbidden() {
        View::render("forbidden", ["user" => $this->userData]);
    }
    
    public function faqs() {
        View::render("faqs", ["user" => $this->userData]);
    }
}
