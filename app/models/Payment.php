<?php
namespace App\Models;
use Config\Database;
use App\Core\Models;

abstract class Payment {
    protected $pdo;
    protected $amount;
    protected $currency;
    protected $transactionId;
    protected $userId;
    protected $ticketId;
    protected $eventId;

    public function __construct($userId, $ticketId, $eventId) {
        $this->pdo = Database::getInstance();
        $this->userId = $userId;
        $this->ticketId = $ticketId;
        $this->eventId = $eventId;
    }

    abstract public function createCheckoutSession($amount, $currency);
    abstract public function processPayment($paymentData);

    public function getPaymentByTransactionId($transactionId) {
        return Models::readByCondition("payments", "transaction_id", $transactionId);
    }

    public function updatePaymentStatus($payment) {
        Models::update("payments", ["status" => $payment->status], "id", $payment->id);
    }

    public function savePayment($paymentGateway, $transactionId, $amount, $currency, $status) {
        $query = "INSERT INTO payments (user_id, ticket_id, transaction_id, amount, currency, status, payment_method)
                  VALUES (:user_id, :ticket_id, :transaction_id, :amount, :currency, :status, :payment_method)";
        $stmt = $this->pdo->prepare($query);
        $data = [
            ':user_id' => $this->userId,
            ':ticket_id' => $this->ticketId,
            ':transaction_id' => $transactionId,
            ':amount' => $amount,
            ':currency' => $currency,
            ':status' => $status,
            ':payment_method' => $paymentGateway
        ];
        return $stmt->execute($data);
    }
    public function insertTicket($amount) {
        $data = [
            "user_id" => $this->userId,
            "event_id" => $this->eventId,
            "price" => $amount
        ];
        return Models::create("ticket", $data);
    }
}