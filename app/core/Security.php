<?php

namespace App\Core;

class Security
{
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public static function isAuthenticated() {
        return isset($_SESSION['user']);
    }

    public static function isAuthorized($requiredRole) {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === $requiredRole;
    }

}
