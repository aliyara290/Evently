<?php

namespace App\Controllers\Back;

use App\Core\View;
use App\Core\Session;

class AppController
{
    private $userData;

    public function __construct()
    {
        $this->userData = Session::get("user");
    }


    public function dashboard()
    {
        View::render("dashboard", ["user" => $this->userData]);
    }
    public function events()
    {
        View::render("manageEvents", ["user" => $this->userData]);
    }
    public function tags()
    {
        View::render("tags", ["user" => $this->userData]);
    }
    public function UpdateTag()
    {
        View::render("updateTag", ["user" => $this->userData]);
    }
    public function updateCategory()
    {
        View::render("updateCategory", ["user" => $this->userData]);
    }
    public function categories()
    {
        View::render("categories", ["user" => $this->userData]);
    }
    public function users()
    {
        View::render("users", ["user" => $this->userData]);
    }
}
