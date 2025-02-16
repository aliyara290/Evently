<?php
namespace App\Models;

use Config\Database;
use App\Core\Models;
use PDO;
use PDOException;

class Ticket{
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

    public function setId($id) {
        $this->id = $id;
    }
    public function setEventId($event_id) {
        $this->eventId = $event_id;
    }

    public function setUserId($user_id) {
        $this->userId = $user_id;
    }

    public function setPrice($price) {
        $this->price = $price;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function insertTicket($amount) {
        $data = [
            "user_id" => $this->userId,
            "event_id" => $this->eventId,
            "price" => $amount
        ];
        echo $this->userId;
        echo $this->eventId;
        echo $amount;
        return Models::create("ticket", $data);
    }
    

    public function getTicketById(int $id): array
    {
        return Models::readByCondition("ticket", "id", $id);
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
            echo("Database error: " . $e->getMessage());
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
        echo 'ttt: ' . $eventId;
        echo 'sss: ' . $places;
        $sql = "UPDATE event SET places = places - :places WHERE id = :event_id";
        $stmt = $this->pdo->prepare($sql);
        $check = $stmt->execute([
            ":event_id" => $eventId,
            ":places" => $places
        ]);
        return $check;
    }
}