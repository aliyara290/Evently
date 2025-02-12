<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Core\Session;
use App\models\Event;

class CreateEventController
{
    private $userData;
    private $eventData;

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->eventData = new Event();
    }

    public function page()
    {
        $categories = $this->eventData->getCategories();
        $regions = $this->eventData->getRegion();
        $cities = $this->eventData->getCities();
        View::render("create/create", ["user" => $this->userData, "categories" => $categories, "regions" => $regions, "cities" => $cities]);
    }

}