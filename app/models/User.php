<?php

namespace App\Models;

use App\Core\Models;
use App\Core\Security;
use App\Core\Validator;
use Exception;

 class User
{
    protected $role;
    protected string $identifier;
    protected ?string $firstName = null;
    protected ?string $lastName = null;
    protected ?string $username = null;
    protected string $email;
    protected ?string $password = null;
    protected ?string $googleId = null;
    protected ?string $avatar = null;

    public function __construct(string $role) 
    {
        $this->role = $role;
    }

    public function setRole($role): void {
        $this->role = $role;
    }
    public function getRole($role) {
        return $this->role;
    }

    public function setIdentifier(string $identifier): void {
        $this->identifier = $identifier;
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setGoogleId(string $googleId): void {
        $this->googleId = $googleId;
    }

    public function setAvatar(string $avatar): void {
        $this->avatar = $avatar;
    }

    public function register()
    {
        $firstName = $this->firstName;
        $lastName = $this->lastName;
        $username = $this->username;
        $email = $this->email;
        $password = $this->password;

        if($password !== null) {
            $password = password_hash($password, PASSWORD_BCRYPT);
        }
        $data = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "userName" => $username,
            "email" => $email,
            "password_hash" => $password,
            "google_id" => $this->googleId,
            "avatar" => $this->avatar,
        ];
        try {
            return Models::create("users", $data);
        } catch (Exception $e) {
            echo 'failde to insert data: ' . $e->getMessage();
        }
    }
    public function findByEmail($email) {
        try {
            return Models::readByCondition("users", "email", $email);
        } catch(Exception $e) {
            echo "failed to find the email: " . $e->getMessage();
        }
    }

    public function login() {
        try {
            $user =  Models::readByCondition("users", "email", $this->email);
            if($user && password_verify($this->password, $user["password"])) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "failed to find the email: " . $e->getMessage();
            return false;
        }
    }
    
}
