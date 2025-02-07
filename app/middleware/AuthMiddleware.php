<?php 
namespace App\Middleware;

class AuthMiddleware {
    public function handle(array $allowRoles): bool {
        session_start();
        $role = $_SESSION["role"] ?? null;
        if(!$role && !in_array($role, $allowRoles)) {
            return false;
        }
        return true;
    }
}