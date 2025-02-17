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
//        var_dump($_GET['id']);
        $this->eventData->setId($_GET['id']);
        $event=$this->eventData->getEventById($_GET['id']);
//        var_dump($event);

        View::render("event/page", ["user" => $this->userData,"event" => $event]);
    }

    public function Resererpage() {
        View::render("event/Réserver");
    }

    public function tecktepage() {
        View::render("event/tecket");
    }
    
}