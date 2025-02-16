<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use Config\Database;
use PDO;
use PDOException;

class Ticket{
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

    public function createTicket(): bool
    {
        $query = "INSERT INTO ticket (event_id, user_id, price, status) 
                  VALUES (:event_id, :user_id, :price, :status)";

        $data = [
            ":event_id" => $this->eventId,
            ":user_id" => $this->userId,
            ":price" => $this->price,
            ":status" => $this->status,
        ];

        try {
            $result = $this->pdo->prepare($query);
            return $result->execute($data);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function deleteTicket(int $id): bool
    {
        $query = "DELETE FROM ticket WHERE id = :id";
        $data = [":id" => $id];

        try {
            $result = $this->pdo->prepare($query);
            return $result->execute($data);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function getTicketById(int $id): array
    {
        $query = "SELECT * FROM ticket WHERE id = :id";
        $data = [":id" => $id];

        try {
            $result = $this->pdo->prepare($query);
            $result->execute($data);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function getTicketsByEventId(int $eventId): array
    {
        $query = "SELECT * FROM ticket WHERE event_id = :event_id";
        $data = [":event_id" => $eventId];

        try {
            $result = $this->pdo->prepare($query);
            $result->execute($data);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function getTicketsByUserId(int $userId): array
    {
        $query = "SELECT * FROM ticket WHERE user_id = :user_id";
        $data = [":user_id" => $userId];

        try {
            $result = $this->pdo->prepare($query);
            $result->execute($data);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }

    public function decrementAvailableTickets($eventId)
    {
        $query = "UPDATE event SET places = places - 1 WHERE id = :event_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([":event_id" => $eventId]);
    }
}