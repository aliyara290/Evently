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

    private string $title;
    private string $description;
    private string $image;

    private string $category;
    private string $eventMode;
    private string $places;
    private string $location;
    private string $type;
    private string $startDate;
    private string $endDate;
    private string $link;
    private string $status;

    private string $price;

    private array $sponsorings = [];

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function setId(int $id): void
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

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function setRegionId(int $regionId): void
    {
        $this->regionId = $regionId;
    }

    public function setCityId(int $cityId): void
    {
        $this->cityId = $cityId;
    }

    public function setIsValidate(bool $isValidate): void
    {
        $this->isValidate = $isValidate;
    }

    public function setEventLink(string $eventLink): void
    {
        $this->eventLink = $eventLink;
    }

    public function setSponsorings(array $sponsorings): void
    {
        $this->sponsorings = $sponsorings;
    }


    public function readAllEvents()
    {
        $query = "select users.userName as username,users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
                    from event
                    join users on users.id=event.user_id
                    join categories on categories.id=event.category_id
                    join region on region.id=event.region_id
                    join city ON city.id = event.city_id
                    join event_sponsorings on event_sponsorings.event_id=event.id
                    join sponsorings on event_sponsorings.sponsoring_id=sponsorings.id
                    GROUP BY 
                        users.userName, users.firstName, users.lastName, event.title, event.description, event.image, categories.name, 
                        event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                        event.isValidate, event.event_link, event.status, region.region, city.ville;
                  ";

        try {
            $result = $this->pdo->prepare($query);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
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
        $columns = "user_id, title, description, image, category_id, event_mode, places, price, start_date, end_date, isValidate, event_link, region_id, city_id";
        $values = ":user_id, :title, :description, :image, :category_id, :event_mode, :places, :price, :start_date, :end_date, :isValidate, :event_link, :region_id, :city_id";

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
            ":isValidate" => $this->isValidate,
            ":event_link" => $this->eventLink,
            ":region_id" => $this->regionId,
            ":city_id" => $this->cityId
        ];
        try {
            $result = $this->pdo->prepare($query);
            $result->execute($data);
        } catch (PDOException $error) {
            echo "Failed to add course: " . $error->getMessage();
            return false;
        }
        $eventId = $this->pdo->lastInsertId();
        foreach ($this->sponsorings as $sponsoringId) {
            $this->addSponsoringToEvent($eventId, $sponsoringId);
        }
    }

    private function removeSponsoringsFromEvent($id)
    {
        $query = "DELETE FROM event_sponsorings WHERE event_id = :event_id";
        $result = $this->pdo->prepare($query);
        $result->execute([':event_id' => $id]);
    }

    private function addSponsoringToEvent(int $eventId, int $sponsoringId)
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
        $query = "select users.userName as username,users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
                      from event
                      join users on users.id=event.user_id
                      join categories on categories.id=event.category_id
                      join region on region.id=event.region_id
                      join city ON city.id = event.city_id
                      join event_sponsorings on event_sponsorings.event_id=event.id
                      join sponsorings on event_sponsorings.sponsoring_id=sponsorings.id
                      where event.id= :id
                      GROUP BY 
                          users.userName, users.firstName, users.lastName, event.title, event.description, event.image, categories.name, 
                          event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                          event.isValidate, event.event_link, event.status, region.region, city.ville;
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




}