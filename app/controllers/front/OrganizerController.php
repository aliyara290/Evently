<?php 
namespace App\Controllers\Front;
use App\Core\Session;
use App\Core\View;
use App\models\Event;

class OrganizerController {

    private $userData;
    private $eventData;

    public function __construct()
    {
        $this->userData = Session::get("user");
        $this->eventData = new Event();
    }

    public function page() {
        View::render("organizer/dashboard");
    }
  

    public function Event_management_page() {
        $this->eventData->setId($this->userData['id']);
        $events = $this->eventData->getOrganizerEvents($this->userData['id']);
        View::render("organizer/Event_management",["events"=>$events]);
    }

    public function deleteOrganizerEvent()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->eventData->deleteEvent($id);
            header("Location: /organizer/manage-events");
            exit();
        }
    }

}