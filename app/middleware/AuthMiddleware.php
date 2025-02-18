<?php 
namespace App\Middleware;

class AuthMiddleware {
    public function handle(array $allowRoles): bool {
        $role = $_SESSION["user"]["active_role"] ?? null;
        // echo $role;
        if(!in_array($role, $allowRoles)) {
            return false;
        }
        return true;
    }
}