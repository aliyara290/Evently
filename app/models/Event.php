<?php

namespace App\models;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Models;
use Config\Database;
use PDO;
use PDOException;

class Event
{
    private int $id;
    private $pdo;

    private string $image;
    private string $title;
    private string $description;
    private string $content;


    private string $category;


    private string $eventMode;
    private string $places;
    private string $region;
    private string $city;
    private string $type;
    private string $startDate;
    private string $endDate;
    private string $link;
    private string $status;

    private string $price;

    private $eventDate;

    private $eventTime;

    private array $sponsorings = [];

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setEventMode(string $eventMode): void
    {
        $this->eventMode = $eventMode;
    }

    public function setPlaces(string $places): void
    {
        $this->places = $places;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function setSponsorings(array $sponsorings): void
    {
        $this->sponsorings = $sponsorings;
    }

    public function setEventLink(string $eventLink): void
    {
        $this->eventLink = $eventLink;
    }

    public function setRegionId(int $regionId): void
    {
        $this->regionId = $regionId;
    }

    public function setCityId(int $cityId): void
    {
        $this->cityId = $cityId;
    }

    public function setEventDate(string $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    public function setEventTime(string $eventTime): void
    {
        $this->eventTime = $eventTime;
    }

    public function readAllEvents()
    {
//        echo "work";
        $query = "select users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,content,event_date,event_time,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
                    from event
                    join users on users.id=event.user_id
                    join categories on categories.id=event.category_id
                    join region on region.id=event.region_id
                    join city ON city.id = event.city_id
                    left join event_sponsorings on event_sponsorings.event_id=event.id
                    left join sponsorings on event_sponsorings.sponsoring_id=sponsorings.id
                    GROUP BY 
                         event.id,users.firstName, users.lastName, event.title,event.content, event.description, event.image, categories.name, 
                        event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                        event.isValidate, event.event_link, event.status, region.region, city.ville,event_date,event_time
order by event.id desc
                  ";


        try {
            $result = $this->pdo->prepare($query);

            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
            var_dump($result->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $error) {
            error_log("Database error: " . $error->getMessage());
            return false;
        }

    }

    public function deleteEvent()
    {
        $query = "delete from event where id = :id";
        $result = $this->pdo->prepare($query);
        $check = $result->execute([':id' => $this->id]);
        return $check;
    }

    public function createEvent()
    {
        $columns = "user_id, title, description, image, category_id, event_mode, places, price, start_date, end_date, isValidate, event_link, region_id, city_id, content, event_date, event_time";
        $values = ":user_id, :title, :description, :image, :category_id, :event_mode, :places, :price, :start_date, :end_date, :isValidate, :event_link, :region_id, :city_id, :content, :event_date, :event_time";

        $query = "INSERT INTO event ($columns) VALUES ($values)";
        $data = [
            ":user_id" => $this->id,
            ":title" => $this->title,
            ":description" => $this->description,
            ":image" => $this->image,
            ":category_id" => $this->category,
            ":event_mode" => $this->eventMode,
            ":places" => $this->places,
            ":price" => $this->price,
            ":start_date" => $this->startDate,
            ":end_date" => $this->endDate,
            ":isValidate" => 1,
            ":event_link" => $this->eventLink,
            ":region_id" => $this->regionId,
            ":city_id" => $this->cityId,
            ":content" => $this->content,
            ":event_date"=> $this->eventDate,
            ":event_time"=>$this->eventTime
        ];
        try {
            $result = $this->pdo->prepare($query);
            return $result->execute($data);
        } catch (PDOException $error) {
            echo "Failed to add course: " . $error->getMessage();
            return false;
        }
        $eventId = $this->pdo->lastInsertId();
        foreach ($this->sponsorings as $sponsoringId) {
            $this->addSponsoringToEvent($eventId, $sponsoringId);
        }
    }

    public function removeSponsoringsFromEvent($id)
    {
        $query = "DELETE FROM event_sponsorings WHERE event_id = :event_id";
        $result = $this->pdo->prepare($query);
        $result->execute([':event_id' => $id]);
    }

    public function addSponsoringToEvent(int $eventId, int $sponsoringId)
    {
        $query = "INSERT INTO event_sponsorings (event_id, sponsoring_id) VALUES (:event_id, :sponsoring_id)";
        $data = [
            ":event_id" => $eventId,
            ":sponsoring_id" => $sponsoringId
        ];
        try {
            $result = $this->pdo->prepare($query);
            $result->execute($data);
        } catch (PDOException $error) {
            echo "Failed to insert data into event_sponsorings table: " . $error->getMessage();
        }
    }

    public function updateEvent(int $id): bool
    {
        $sql = "UPDATE event 
                SET 
                    title = :title,
                    description = :description,
                    image = :image,
                    category_id = :category_id,
                    event_mode = :event_mode,
                    places = :places,
                    price = :price,
                    start_date = :start_date,
                    end_date = :end_date,
                    isValidate = :isValidate,
                    event_link = :event_link,
                    region_id = :region_id,
                    city_id = :city_id,
                    status = :status
                WHERE id = :id";

        $data = [
            ":title" => $this->title,
            ":description" => $this->description,
            ":image" => $this->image,
            ":category_id" => $this->category,
            ":event_mode" => $this->eventMode,
            ":places" => $this->places,
            ":price" => $this->price,
            ":start_date" => $this->startDate,
            ":end_date" => $this->endDate,
            ":isValidate" => $this->isValidate,
            ":event_link" => $this->eventLink,
            ":region_id" => $this->regionId,
            ":city_id" => $this->cityId,
            ":status" => $this->status,
            ":id" => $id
        ];

        try {

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($data);

            $this->removeSponsoringsFromEvent($id);
            foreach ($this->sponsorings as $sponsoringId) {
                $this->addSponsoringToEvent($id, $sponsoringId);
            }

            return $result;

        } catch (PDOException $error) {
            echo "Failed to update event: " . $error->getMessage();
            return false;
        }
    }


    public function getEventById(int $id)
    {
        $query = "select users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,content,event_date,event_time,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
                    from event
                    join users on users.id=event.user_id
                    join categories on categories.id=event.category_id
                    join region on region.id=event.region_id
                    join city ON city.id = event.city_id
                    left join event_sponsorings on event_sponsorings.event_id=event.id
                    left join sponsorings on event_sponsorings.sponsoring_id=sponsorings.id
                    where event.id= :id
                    GROUP BY 
                         users.firstName, users.lastName, event.title,event.content, event.description, event.image, categories.name, 
                        event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                        event.isValidate, event.event_link, event.status, region.region, city.ville,event_date,event_time
                  ";
        $data = [
            ":id" => $this->id
        ];

        try {
            $result = $this->pdo->prepare($query);
            $result->execute($data);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Database error: " . $error->getMessage());
            return false;
        }

    }

    public function countAllEvents(){
        $query="SELECT COUNT(*) as total FROM event";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getCategories(){
        $query= "SELECT * FROM categories";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegion(){
        $query= "SELECT * FROM region";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCities(){
        $query= "SELECT * FROM city";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSponsorings(){
        $query= "SELECT * FROM sponsorings";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastEventId(){
        $query="SELECT * from event order by created_At desc limit 1";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }




}