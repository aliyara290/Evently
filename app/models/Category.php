<?php 
namespace App\Models;
use PDO;
class Category {
    public static function createCategory($pdo, $name) {
        $sql = "INSERT INTO categories(name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
