<?php 
namespace App\Core;

class Validator {

    public static function sanitize($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail($email) {
        $email = self::sanitize($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
    }

    public static function validatePassword($password) {
        $password = self::sanitize($password);
        return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/\d/', $password) ? $password : false;
    }

    public static function validateUsername($username) {
        $username = self::sanitize($username);
        return preg_match('/^[a-zA-Z0-9_]{3,15}$/', $username) ? $username : false;
    }


    public static function validateDate($date, $format = 'Y-m-d') {
        $d = strtotime($date);
        return ($d && date($format, $d) === $date) ? $date : false;
    }


    public static function validatePrice($price) {
        $price = self::sanitize($price);
        return preg_match('/^\d+(\.\d{1,2})?$/', $price) ? $price : false;
    }
    
}