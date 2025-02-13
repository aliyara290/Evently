<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Core\Session;
use App\Models\Event;

class EventsController
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
        $events = $this->eventData->readAllEvents();
//        var_dump($events);
        View::render("events", ["user" => $this->userData, "records" => $events]);

    }

    public function create()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

//                echo $_SESSION['user']['id'];
                $this->eventData->setId(11);

                $this->eventData->setTitle($_POST['event_title']);
                $this->eventData->setDescription($_POST['description']);
                $this->eventData->setContent($_POST['content']);

                $image = $_FILES['image']['name'];
                $temp_file = $_FILES['image']['tmp_name'];
                $folder = "./assets/upload/" . $image;
                move_uploaded_file($temp_file, $folder);

                $this->eventData->setImage($image);
                $this->eventData->setCategory($_POST['category']);
                $eventDate = $_POST['event_date'];
                $eventTime = $_POST['event_time'];
                var_dump($eventDate);
                $this->eventData->setEventDate($eventDate);
                $this->eventData->setEventTime($eventTime);
                $this->eventData->setEventMode(isset($_POST['venue']) ? 'presentiel' : 'enligne');
                $this->eventData->setRegionId($_POST['region']);
                var_dump($_POST['region']);
                $this->eventData->setCityId($_POST['city']);

                $this->eventData->setPlaces($_POST['places']);

                $price = empty($_POST['price']) ? '0' : $_POST['price'];

                $this->eventData->setPrice($price);


                // Format and set sales start period
                $salesStartDate = $_POST['event_start'];
                $salesStartTime = $_POST['event_time'];
                $formattedSalesStart = date('Y-m-d H:i:s', strtotime("$salesStartDate $salesStartTime"));

                // Format and set sales end period
                $salesEndDate = $_POST['event_end'];
                $salesEndTime = $_POST['event_time'];
                $formattedSalesEnd = date('Y-m-d H:i:s', strtotime("$salesEndDate $salesEndTime"));

                $this->eventData->setStartDate($formattedSalesStart);
                $this->eventData->setEndDate($formattedSalesEnd);

                $this->eventData->setStatus('pending');
                $this->eventData->setEventLink($_POST['link']);


                $this->eventData->createEvent();
                $getLastEventId = $this->eventData->getLastEventId();
//                var_dump($getLastEventId);

                foreach ($_POST['sponsorings_id'] as $sponsoringId) {
                    $test=$this->eventData->addSponsoringToEvent($getLastEventId['id'], $sponsoringId);
                    var_dump($test);
                }
//                if ($result) {
//                    header("location: /events");
//                    exit();
//                } else {
//                    throw new \Exception('Failed to create event');
//                }
            } catch (\Exception $e) {
                Session::set('error', $e->getMessage());
                View::render("/events", [
                    "user" => $this->userData,
                    "error" => $e->getMessage()
                ]);
            }
        }
    }


}