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
    public function afficherCategories ($pdo){
        $sql = "SELECT * FROM categories";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 

    }
    public function deleteCategory ($pdo,$id){
        $sql = "DELETE FROM  categories WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id",$id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateCategory($pdo, $id, $newName) {
        $sql = "UPDATE categories SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $newName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function TotalCategories($pdo) {
        $sql = "SELECT COUNT(*) as total FROM categories";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['total'] : 0; 
    }
    
    
}
    