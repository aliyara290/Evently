<?php 
namespace App\Controllers\Front;
use App\Core\View;

class AppController {
    public function notFound() {
        View::render("404");
    }
    public function forbidden() {
        View::render("forbidden");
    }
    public function faqs() {
        View::render("faqs");
    }
}