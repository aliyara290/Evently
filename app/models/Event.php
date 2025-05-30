<?php

namespace App\models;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Models;
use Config\Database;
use App\Controllers\Mail\MailController;
use PDO;
use PDOException;

class Event
{
    private int $id;
    private $pdo;
    private $mail;
    private string $image;
    private string $title;
    private string $description;
    private string $content;
    private bool $isValidate = true;
    private string $category;
    private string $eventMode;
    private string $places;
    private string $regionId;
    private string $cityId;
    private string $type;
    private string $startDate;
    private string $city;
    private string $region;
    private string $endDate;
    private string $eventLink;
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
        $this->eventLink = $link;
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



    public function readAllEvents()
    {
        $query = "select event.id,users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,content,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
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
                        event.isValidate, event.event_link, event.status, region.region, city.ville
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

    public function deleteEvent($id)
    {
        $query = "delete from event where id = :id";
        $result = $this->pdo->prepare($query);
        $check = $result->execute([':id' => $id]);
        return $check;

    }
    public function redAllEventsActive()
{
                $query = "SELECT event.id, users.firstName AS firstName, users.lastName AS lastName, 
                event.title, event.description, event.image, categories.name AS category, 
                event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                event.isvalidate, event.event_link, event.status, region.region, city.ville, 
                event.content, event.event_date, event.event_time, 
                STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
            FROM event
            JOIN users ON users.id = event.user_id
            JOIN categories ON categories.id = event.category_id
            JOIN region ON region.id = event.region_id
            JOIN city ON city.id = event.city_id
            LEFT JOIN event_sponsorings ON event_sponsorings.event_id = event.id
            LEFT JOIN sponsorings ON event_sponsorings.sponsoring_id = sponsorings.id
            WHERE event.status = 'accepted' 
            GROUP BY event.id, users.firstName, users.lastName, event.title, event.content, 
                event.description, event.image, categories.name, event.event_mode, event.places, 
                event.price, event.start_date, event.end_date, event.isValidate, event.event_link, 
                event.status, region.region, city.ville, event.event_date, event.event_time
            ORDER BY event.id DESC";

            try {
            $result = $this->pdo->prepare($query);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $error) {
            error_log("Database error: " . $error->getMessage());
            return [];
            }
}





public function redAllEventsPending()
{
    $query = "SELECT event.id, users.firstName AS firstName, users.lastName AS lastName, 
                     event.title, event.description, event.image, categories.name AS category, 
                     event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                     event.isvalidate, event.event_link, event.status, region.region, city.ville, 
                     event.content, event.event_date, event.event_time, 
                     STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
              FROM event
              JOIN users ON users.id = event.user_id
              JOIN categories ON categories.id = event.category_id
              JOIN region ON region.id = event.region_id
              JOIN city ON city.id = event.city_id
              LEFT JOIN event_sponsorings ON event_sponsorings.event_id = event.id
              LEFT JOIN sponsorings ON event_sponsorings.sponsoring_id = sponsorings.id
              WHERE event.status = 'pending' 
              GROUP BY event.id, users.firstName, users.lastName, event.title, event.content, 
                       event.description, event.image, categories.name, event.event_mode, event.places, 
                       event.price, event.start_date, event.end_date, event.isValidate, event.event_link, 
                       event.status, region.region, city.ville, event.event_date, event.event_time
              ORDER BY event.id DESC";

    try {
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        error_log("Database error: " . $error->getMessage());
        return [];
    }
}

public function updateStatusEvent($id, $pdo,)
{

    $status = 'accepted';

    $sql = 'UPDATE event SET status = :status WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_BOOL);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $check = $stmt->execute();
    return $check;
}

public function deleteEventId($id)
{
    try {
        $query = "DELETE FROM event WHERE id = :id";
        $result = $this->pdo->prepare($query);
        $result->bindValue(':id', $id, PDO::PARAM_INT);

        if ($result->execute()) {
            return "L'événement a été supprimé avec succès."; // Succès
        } else {
            return "Échec de la suppression de l'événement."; // Échec
        }
    } catch (PDOException $error) {
        error_log("Erreur de base de données : " . $error->getMessage());
        return "Une erreur est survenue lors de la suppression de l'événement.";
    }
}

public function updateStatusEventRrefuse($id, $pdo,)
{

    $status = 'pending';

    $sql = 'UPDATE event SET status = :status WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_BOOL);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
}

public function createEvent()
{
    $columns = "user_id, title, description, image, category_id, event_mode, places, price, start_date, end_date, isValidate, event_link, region_id, city_id, content";
    $values = ":user_id, :title, :description, :image, :category_id, :event_mode, :places, :price, :start_date, :end_date, :isValidate, :event_link, :region_id, :city_id, :content";

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
        ":content" => $this->content
    ];

    try {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    } catch (PDOException $error) {
        echo "Failed to add event: " . $error->getMessage();
        return false;
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
                event_link = :event_link,
                status = :status,
                region_id = :region_id,
                city_id = :city_id,
                content = :content
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
            ":event_link" => $this->eventLink,
            ":status" => $this->status,
            ":region_id" => $this->regionId,
            ":city_id" => $this->cityId,
            ":content" => $this->content,
            ":id" => $id
        ];

        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($data);

            if ($result && isset($this->sponsorings)) {
                $this->removeSponsoringsFromEvent($id);
                foreach ($this->sponsorings as $sponsoringId) {
                    $this->addSponsoringToEvent($id, $sponsoringId);
                }
            }

            return $result;

        } catch (PDOException $error) {
            error_log("Failed to update event: " . $error->getMessage());
            return false;
        }
    }


    public function getEventById(int $id)
    {
        $query = "select event.*,users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date::DATE AS startDate,
                    start_date::TIME AS startTime,end_date::DATE AS endDate,end_date::TIME AS endTime,region.region,city.ville,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos,array_agg(event_sponsorings.sponsoring_id) AS selected_sponsorings                
                    from event
                    join users on users.id=event.user_id
                    join categories on categories.id=event.category_id
                    join region on region.id=event.region_id
                    join city ON city.id = event.city_id
                    left join event_sponsorings on event_sponsorings.event_id=event.id
                    left join sponsorings on event_sponsorings.sponsoring_id=sponsorings.id
                    where event.id= :id
                    GROUP BY 
                       event.id,users.firstName, users.lastName, event.title,event.content, event.description, event.image, categories.name, 
                        event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                        event.isValidate, event.event_link, event.status, region.region, city.ville
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
    public function searchForCity($query)
    {
        $sql = "SELECT * FROM city WHERE ville ILIKE ? ORDER BY ville ASC LIMIT 5";
        $stmt = $this->pdo->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: false;
    }

    public function searchForEvents($eventTerm, $city)
    {
        $sql = "SELECT 
    event.image,
    event.title,  
    event.event_date,  
    event.description,  
    city.ville,  
    event.price  
FROM event
JOIN city ON city.id = event.city_id
WHERE 
    (COALESCE(:eventTerm, '') = '' OR event.title ILIKE :eventTerm OR event.description ILIKE :eventTerm)
    AND (COALESCE(:city, '') = '' OR city.ville ILIKE :city)
GROUP BY 
    event.id, 
    event.image, 
    event.title, 
    event.event_date, 
    event.description, 
    city.ville, 
    event.price;
    ";

        $stmt = $this->pdo->prepare($sql);
        $searchTerm = !empty($eventTerm) ? "%$eventTerm%" : null;
        $searchCity = !empty($city) ? "%$city%" : null;

        $stmt->bindValue(':eventTerm', $searchTerm, $searchTerm !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(':city', $searchCity, $searchCity !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: false;
    }

    public function filter(string $category, $date)
    {
      
        $sql = "SELECT 
                event.image,
                event.id,
                event.title,  
                event.start_date,  
                event.description,  
                city.ville,  
                event.price  
                FROM event
                JOIN city ON city.id = event.city_id
                JOIN categories ON categories.id = event.category_id
                WHERE 
                (COALESCE(:categoryName, '') = '' OR categories.name ILIKE :categoryName)
                -- AND (event.havePrice IS false OR event.havePrice = CAST(:price AS BOOLEAN)) 
                AND (COALESCE(:eventDate, '') = '' OR event.event_date = CAST(:eventDate AS DATE))
                GROUP BY 
                event.id, 
                event.image, 
                event.title, 
                event.event_date, 
                event.description, 
                city.ville, 
                event.price;";
    
        $stmt = $this->pdo->prepare($sql);
    
        $category = !empty($category) ? "%$category%" : null;
        // $price = ($price !== '') ? (bool) $price : null;
        $date = !empty($date) ? $date : null;
    
        $stmt->bindValue(':categoryName', $category, $category !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        // $stmt->bindValue(':price', $price);
        $stmt->bindValue(':eventDate', $date, $date !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: false;
    }
    
    public function getSponsorings(){
        $query= "SELECT * FROM sponsorings";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSponsoringsById($id){
        $query= "select
                e.*,
                s.logo,
                s.name
                from event_sponsorings e 
                JOIN sponsorings s ON e.sponsoring_id = s.id
                WHERE e.event_id = :id";
        $result = $this->pdo->prepare($query);
        $result->execute([":id" => $id]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastEventId(){
        $query="SELECT * from event order by created_At desc limit 1";
        $result = $this->pdo->prepare($query);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrganizerEvents(){
        $query = "select event.*,users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name as category_name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,content,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
                    from event
                    join users on users.id=event.user_id
                    join categories on categories.id=event.category_id
                    join region on region.id=event.region_id
                    join city ON city.id = event.city_id
                    left join event_sponsorings on event_sponsorings.event_id=event.id
                    left join sponsorings on event_sponsorings.sponsoring_id=sponsorings.id
                    where event.user_id= :id
                    GROUP BY 
                       event.id,users.firstName, users.lastName, event.title,event.content, event.description, event.image, categories.name, 
                        event.event_mode, event.places, event.price, event.start_date, event.end_date, 
                        event.isValidate, event.event_link, event.status, region.region, city.ville
                    order by event.id desc
                  ";

        $data = [
            ":id" => $this->id
        ];
        try {
            $result = $this->pdo->prepare($query);

            $result->execute($data);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            error_log("Database error: " . $error->getMessage());
            return false;
        }
    }

    public function getlastEightEvents(){
        $query = "select event.id,users.firstName as firstName,users.lastName as lastName,title,description,image ,categories.name,event_mode,places,price,start_date,end_date,isvalidate,event_link,status,region.region,city.ville,content,STRING_AGG(sponsorings.logo, ', ') AS sponsor_logos
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
                        event.isValidate, event.event_link, event.status, region.region, city.ville
                  order by event.id desc
                  limit 8
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
    public function TotalEvent($pdo){
        $sql = "SELECT COUNT(*) as total FROM event";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['total'] : 0; 
    }

    public function TotalEventpending($pdo){
        $sql = "SELECT COUNT(*) as total FROM event WHERE status = 'pending' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['total'] ?? 0; 
    }
    public function TotalEventaccepted($pdo){
        $sql = "SELECT COUNT(*) as total FROM event WHERE status = 'accepted' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['total'] ?? 0; 
    }

    public function getCitiesByRegion($regionId) {
        $query = "SELECT * FROM city WHERE region = :region_id";
        $result = $this->pdo->prepare($query);
        $result->execute(['region_id' => $regionId]);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
