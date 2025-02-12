<?php

namespace App\Models;

use App\Core\Models;
use App\Core\Security;
use App\Core\Validator;
use PDOException;
use Exception;
use PDO;

class User
{
    protected $role;
    protected ?string $firstName = null;
    protected ?string $lastName = null;
    protected string $email;
    protected ?string $password = null;
    protected ?string $googleId = null;
    protected ?string $avatar = null;

    public function setRole($role): void
    {
        $this->role = $role;
    }
    public function getRole() {
        return $this->role;
    }


    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function register()
    {
        $firstName = $this->firstName;
        $lastName = $this->lastName;
        $email = $this->email;
        $password = $this->password;

        if ($password !== null) {
            $password = password_hash($password, PASSWORD_BCRYPT);
        }
        $data = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "email" => $email,
            "password_hash" => $password,
            "google_id" => $this->googleId,
            "avatar" => $this->avatar,
        ];
        try {
            Models::create("users", $data);
            return true;

        } catch (Exception $e) {
            echo 'failde to insert data: ' . $e->getMessage();
        }
    }
    public function findByEmail($email)
    {
        try {
            return Models::readByCondition("users", "email", $email);
        } catch (Exception $e) {
            echo "failed to find the email: " . $e->getMessage();
        }
    }


    public function login()
    {
        try {
            $user =  Models::readByCondition("users", "email", $this->email);
            if($user && password_verify($this->password, $user["password"])) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "failed to find the email: " . $e->getMessage();
            return false;
        }
    }


    public function updateUser($id, $pdo,)
    {
        
        $is_suspended = false;

        $sql = 'UPDATE users SET is_suspended = :is_suspended WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':is_suspended', $is_suspended, PDO::PARAM_BOOL);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }
    public function updateUserToActive($id, $pdo)
    {
        $is_suspended = true;
        $sql = 'UPDATE users SET is_suspended  = :is_suspended WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':is_suspended', $is_suspended, PDO::PARAM_BOOL);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }
    public function getAllUsers($pdo)
    {
        $sqlActive = "SELECT id, firstName, lastName, email, password_hash, is_suspended, createdAt 
                      FROM users";
        $stmtActive = $pdo->prepare($sqlActive);
        $stmtActive->execute();
        $usersActive = $stmtActive->fetchAll(PDO::FETCH_ASSOC);
    
        $sqlSuspended = "SELECT id, firstName, lastName, email, password_hash, is_suspended, createdAt 
                         FROM users 
                         WHERE is_suspended = FALSE";
        $stmtSuspended = $pdo->prepare($sqlSuspended);
        $stmtSuspended->execute();
        $usersSuspended = $stmtSuspended->fetchAll(PDO::FETCH_ASSOC);
    
        return [
            'active' => $usersActive,
            'suspended' => $usersSuspended
        ];
    }
    
  

    public function deleteUser($pdo, $id)
    {
        $sql = "DELETE FROM users WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur : " . $e->getMessage());
            return false;
        }
    }
}
