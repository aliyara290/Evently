<?php

namespace App\Models;

use Config\Database;
use App\Core\Models;
use PDO;
use PDOException;

class Ticket
{
    private $pdo;
    private $id;
    private $eventId;
    private $userId;
    private $price;
    private $status;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setEventId($event_id)
    {
        $this->eventId = $event_id;
    }

    public function setUserId($user_id)
    {
        $this->userId = $user_id;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function insertTicket($amount, $places, $total)
    {
        $data = [
            "user_id" => $this->userId,
            "event_id" => $this->eventId,
            "price" => $amount,
            "places" => $places,
            "total_price" => $total
        ];
        $check =  Models::create("ticket", $data);
        if ($check) {
            return $this->pdo->lastInsertId();
        } else {
            return false;
        }
    }


    public function getTicketById(int $id): array
    {
        $sql = "SELECT 
            ticket.id AS ticket_id, 
            CONCAT(users.firstName, ' ', users.lastName) AS name,
            users.email,
            event.title AS event_title, 
            ticket.price AS ticket_price, 
            ticket.total_price, 
            ticket.places AS number_places, 
            event.start_date,
            city.ville,
            ticket.type AS ticket_type 
            FROM ticket
            JOIN event ON ticket.event_id = event.id
            JOIN city ON event.city_id = city.id
            JOIN users ON ticket.user_id = users.id
            WHERE ticket.id = :id;";
        $data = [":id" => $id];

        try {
            $result = $this->pdo->prepare($sql);
            $result->execute($data);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo ("Database error: " . $e->getMessage());
        }
    }

    public function getTicketsByEventId(int $eventId): array
    {
        $sql = "SELECT * FROM ticket WHERE event_id = :event_id";
        $data = [":event_id" => $eventId];

        try {
            $result = $this->pdo->prepare($sql);
            $result->execute($data);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo ("Database error: " . $e->getMessage());
        }
    }

    public function getTicketsByUserId(int $userId): array
    {
        $sql = "SELECT * FROM ticket WHERE user_id = :user_id";
        $data = [":user_id" => $userId];

        try {
            $result = $this->pdo->prepare($sql);
            $result->execute($data);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo ("Database error: " . $e->getMessage());
        }
    }

    public function decrementAvailableTickets($eventId, $places)
    {

        $sql = "UPDATE event SET places = places - :places WHERE id = :event_id";
        $stmt = $this->pdo->prepare($sql);
        $check = $stmt->execute([
            ":event_id" => $eventId,
            ":places" => $places
        ]);
        return $check;
    }
}
