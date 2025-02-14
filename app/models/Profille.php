<?php 
namespace App\Models;
use Config\Database;
use PDO;
class Profille {
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance();
        if (!$this->db) {
            die("Erreur de connexion à la base de données");
        }
    }
    public function updateProfile($id, $firstName, $lastName, $bio, $photo = null) {
        if ($photo && is_array($photo)) {  
            $sql = 'UPDATE users SET firstName = :firstName, lastName = :lastName, avatar = :avatar, bio = :bio WHERE id = :id';
        } else {
            $sql = 'UPDATE users SET firstName = :firstName, lastName = :lastName, bio = :bio WHERE id = :id';
        }
    
        $stmt = $this->db->prepare($sql);
    
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':bio', $bio);
   
    
        return $stmt->execute();
    }
    
    

    public function getUserById($userId)
    {
        $sql = "SELECT id, firstName, lastName, email, password_hash, is_suspended, createdAt ,avatar,bio
                FROM users 
                WHERE id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            return $user;  
        } else {
            return null;  
        }
    }
    
    
    

}