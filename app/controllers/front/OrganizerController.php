<?php

namespace App\Controllers\Front;
use App\Models\Sponsoring;
use Config\Database;

use App\Core\Session;
use App\Core\View;
use App\models\Event;
use App\Core\Validator;


class OrganizerController
{

    private $userData;
    private $eventData;
    private $sponsoringclass;
    private $connection;


    public function __construct()
    {
        $this->connection = Database::getInstance();

        $this->userData = Session::get("user");
        $this->eventData = new Event();
        $this->sponsoringclass = new Sponsoring();

    }

    public function page()
    {
        View::render("organizer/dashboard");
    }
    
    public function updatesponsoringpage()
{
    $sponsorId = $_POST['sponsor_id'];
    $sponsor = $this->sponsoringclass->affichersponsoringsById($this->connection,$sponsorId); 
    View::render("organizer/updateSponsour", ['sponsor' => $sponsor]);
}


    public function PageSponser()
    {
        View::render("organizer/sponsoringMangmebt");
    }
    public function PageCreteSponser()
    {
        View::render("organizer/sponsoringcerate");
    }


    public function Event_management_page()
    {
        $this->eventData->setId($this->userData['id']);
        $events = $this->eventData->getOrganizerEvents();
        View::render("organizer/Event_management", ["events" => $events]);
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

    public function editOrganizerEvent()
    {
        $categories = $this->eventData->getCategories();
        $regions = $this->eventData->getRegion();
        $cities = $this->eventData->getCities();
        $sponsorings = $this->eventData->getSponsorings();
        $this->eventData->setId($_GET['id']);
        $event = $this->eventData->getEventById($_GET['id']);
        $selectedSponsorings = $event['selected_sponsorings'];
//        var_dump($event);
        View::render("organizer/editEvent", ["user" => $this->userData, "categories" => $categories, "regions" => $regions, "cities" => $cities, "sponsorings" => $sponsorings, "event" => $event,"selectedSponsorings" => $selectedSponsorings]);
    }


    public function updateEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $eventId = $_POST['id'];

                $this->eventData->setId($this->userData['id']);

                $this->eventData->setTitle($_POST['event_title']);
                $this->eventData->setDescription($_POST['description']);
                $this->eventData->setContent($_POST['content']);

                if (!empty($_FILES['image']['name'])) {
                    $image = $_FILES['image']['name'];
                    $temp_file = $_FILES['image']['tmp_name'];
                    $folder = "./assets/upload/" . $image;
                    if (move_uploaded_file($temp_file, $folder)) {
                        $this->eventData->setImage($image);
                    } else {
                        throw new \Exception('Failed to upload new image');
                    }
                } else {
                    $this->eventData->setImage($_POST['old_image']);
                }

                $this->eventData->setCategory($_POST['category']);

                $this->eventData->setEventMode(isset($_POST['venue']) ? 'presentiel' : 'enligne');

                $this->eventData->setRegionId($_POST['region'] ?? null);
                $this->eventData->setCityId($_POST['city'] ?? null);

                $this->eventData->setPlaces($_POST['places']);

                $price = Validator::validatePrice($_POST['price']) ? '0' : $_POST['price'];
                $this->eventData->setPrice($price);

                $salesStartDate = Validator::validateDate($_POST['event_start']);
                $salesStartTime = Validator::validatePrice($_POST['event_time']) ?? '00:00';
                $formattedSalesStart = date('Y-m-d H:i:s', strtotime("$salesStartDate $salesStartTime"));

                $salesEndDate = Validator::validateDate($_POST['event_end']);
                $salesEndTime = Validator::validateDate($_POST['event_time']) ?? '00:00';
                $formattedSalesEnd = date('Y-m-d H:i:s', strtotime("$salesEndDate $salesEndTime"));

                $this->eventData->setStartDate($formattedSalesStart);
                $this->eventData->setEndDate($formattedSalesEnd);

                $this->eventData->setEventLink($_POST['link'] ?? '');
                $this->eventData->setStatus('pending');

                if (isset($_POST['sponsorings_id'])) {
                    $this->eventData->setSponsorings($_POST['sponsorings_id']);
                }

                if ($this->eventData->updateEvent($eventId)) {
                    header("location: /organizer/manage-events");
                    exit();
                } else {
                    throw new \Exception('Failed to update event');
                }

            } catch (\Exception $e) {
                Session::set('error', $e->getMessage());
            }
        }
    }



}