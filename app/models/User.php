<?php 
namespace App\Models;
use App\Core\Models;
use App\Core\Security;
use App\Core\Validator;
use Exception;

abstract class User {
    protected $role;
    protected string $identifier;
    protected string $fullName;
    protected string $username;
    protected string $email;
    protected string $password;

    public function setRole(string $role) {
        $this->role = $role;
    }
    public function getRole() {
        return $this->role;
    }
    public function setIdentifier(string $identifier) {
        $this->identifier = $identifier;
    }
    public function getIdentifier() {
        return $this->identifier;
    }
    public function setFullName(string $fullName) {
        $this->fullName = $fullName;
    }
    public function getFullName() {
        return $this->fullName;
    }
    public function setUsername(string $username) {
        $this->username = $username;
    }
    public function getUsername() {
        return $this->username;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
    public function getEmail() {
        return $this->email;
    }
    public function setPassword(string $password) {
        $this->password = $password;
    }
    public function getPassword() {
        return $this->password;
    }
    // create table users (
    //     id int primary key auto_increment,
    //     username varchar(50),
    //     email varchar(50) unique,
    //     password_hash varchar(255),
    //     profile_picture varchar(50)
    //     );
    public function register() {
        $fullName = Validator::sanitize($this->fullName);
        $username = Validator::sanitize($this->username);
        $email = Validator::validateEmail($this->email);
        $password = Validator::validatePassword($this->password);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $data = [
            "name" => $fullName,
            "username" => $username,
            "email" => $email,
            "password" => $passwordHash,
        ];
        try {
            return Models::create("users", $data);
        } catch (Exception $e) {
            echo 'failde to insert data: ' . $e->getMessage();
        }
    }

    public function login() {
        $email = Validator::sanitize($this->email);
        $password = Validator::validatePassword($this->password);
        $role = Validator::sanitize($this->role);
        $data = [
            "email" => $email,
            "password" => $password
        ];
    }
}