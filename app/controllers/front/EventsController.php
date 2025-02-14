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
        $categories = $this->eventData->getCategories();
        $cities = $this->eventData->getCities();
        View::render("events", ["user" => $this->userData, "records" => $events, "categories" => $categories, "cities" => $cities]);
    }

    public function create()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->eventData->setId($this->userData["id"]);

                $this->eventData->setTitle($_POST['event_title']);
                $this->eventData->setDescription($_POST['description']);
                $this->eventData->setContent($_POST['content']);
                $image = null;
                if (isset($_FILES['image'])) {
                    $image = $_FILES['image']['name'];
                    $temp_file = $_FILES['image']['tmp_name'];
                    $folder = "./assets/upload/" . $image;
                    move_uploaded_file($temp_file, $folder);
                }

                $this->eventData->setImage($image);
                $this->eventData->setCategory($_POST['category']);
                $eventDate = $_POST['event_date'];
                $eventTime = $_POST['event_time'];
                $this->eventData->setEventDate($eventDate);
                $this->eventData->setEventTime($eventTime);
                $this->eventData->setEventMode(isset($_POST['venue']) ? 'presentiel' : 'enligne');
                $this->eventData->setRegionId($_POST['region']);
                $this->eventData->setCityId($_POST['city']);
                $this->eventData->setPlaces($_POST['places']);
                $this->eventData->setPrice(isset($_POST['free']) ? '0' : $_POST['price']);
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
                $this->eventData->setEventLink('test');

                $result = $this->eventData->createEvent();

                if ($result) {
                    header("location: /events");
                    exit();
                }
            } catch (\Exception $e) {
                View::render("/events", [
                    "user" => $this->userData,
                    "error" => $e->getMessage()
                ]);
            }
        }
    }

    public function searchForEvents() {
        if (isset($_GET['q'])) {
            $query = trim($_GET['q']);
            $city = trim($_GET['city']);
            $events = $this->eventData->searchForEvents($query, $city);
            header('Content-Type: application/json');
            echo json_encode(["events" => $events]);
            exit();
        } else {
            echo "noooooo";
        }

    }
    public function searchForCity() {
        if (isset($_GET['city'])) {
            $query = trim($_GET['city']);
            $cities = $this->eventData->searchForCity($query);
            header('Content-Type: application/json');
            echo json_encode(["cities" => $cities]);
            exit();
        } else {
            echo "noooooo";
        }
    }
    public function filter() {
        if (isset($_GET['category'])) {
            $category = trim($_GET['category']);
            // $price = trim($_GET['price']);
            // echo $price;
            $date = trim($_GET['date']);
            $eventsFilter = $this->eventData->filter($category, $date);
            header('Content-Type: application/json');
            echo json_encode(["eventsFilter" => $eventsFilter]);
            exit();
        } else {
            echo "noooooo";
        }

    }
    
}

