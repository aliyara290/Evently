<?php 
namespace App\Controllers\Front;
use App\Core\View;
use App\Core\Session;
use App\models\Event;

class EventController {
    private $userData;

    private $eventData;

    public function __construct() {
        $this->userData = Session::get("user");
        $this->eventData = new Event();
    }
    public function page() {
        $this->eventData->setId($_GET['id']);
        $event=$this->eventData->getEventById($_GET['id']);
        $sponsorings=$this->eventData->getSponsoringsById($_GET['id']);

        View::render("event/page", ["user" => $this->userData,"event" => $event, "sponsorings" => $sponsorings]);
    }

    public function Resererpage() {
        View::render("event/Réserver");
    }

    public function tecktepage() {
        View::render("event/tecket");
    }
    
}