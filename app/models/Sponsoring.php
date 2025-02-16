<?php 
namespace App\Models;
use PDO;

class Sponsoring {
    public static function createsponsorings($pdo, $name, $logo) {
        $sql = "INSERT INTO sponsorings(name, logo) VALUES (:name, :logo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    public function affichersponsorings ($pdo){
        $sql = "SELECT * FROM sponsorings";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 

    }

    public function affichersponsoringsById($pdo, $id) {
        $sql = "SELECT * FROM sponsorings WHERE id = :id";
        $stmt = $pdo->prepare($sql);
    
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
    public function deletesponsorings ($pdo,$id){
        $sql = "DELETE FROM  sponsorings WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id",$id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updatesponsorings($pdo, $id, $newName, $newLogo = null) {
        if ($newLogo) {
            $sql = "UPDATE sponsorings SET name = :name, logo = :logo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':logo', $newLogo, PDO::PARAM_STR);
        } else {
            $sql = "UPDATE sponsorings SET name = :name WHERE id = :id";
            $stmt = $pdo->prepare($sql);
        }
    
        $stmt->bindParam(':name', $newName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute();
    }
    


    
    
}
    