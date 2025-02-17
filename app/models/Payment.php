<?php
namespace App\Models;
use Config\Database;
use App\Core\Models;
use App\Core\Session;

abstract class Payment {
    protected $pdo;
    protected $amount;
    protected $currency;
    protected $transactionId;
    protected $userId;
    protected $ticketId;
    protected $eventId;

    public function __construct($userId) {
        $this->pdo = Database::getInstance();
        $this->userId = $userId;
    }

    abstract public function createCheckoutSession($amount, $currency, $event, $places);
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
            ':ticket_id' => Session::get("ticketId"),
            ':transaction_id' => $transactionId,
            ':amount' => $amount,
            ':currency' => $currency,
            ':status' => $status,
            ':payment_method' => $paymentGateway
        ];
        return $stmt->execute($data);
    }
    
}