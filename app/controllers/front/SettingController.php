<?php 
namespace App\Controllers\Front;
 
use App\Core\View;
use App\Core\Session;
use App\Models\Profille;
use APP\Core\Validator;

class SettingController {
    private $userData;
    private $classprofille;
    private $user;

    public function __construct() {
        $this->userData = Session::get("user");
        $this->classprofille = new Profille();
    }
    public function profile() {
        
            $id =  $this->userData['id'];
            $user = $this->classprofille->getUserById($id);
            if ($user) {
                View::render("setting/profile", ["user" => $user]);
            } else {
                echo "no user geted";
            }
    }
    public function setting() {
        View::render("setting/setting", ["user" => $this->userData]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Validator::sanitize($_POST['user_id']) ?? null;
            $firstName = Validator::sanitize($_POST['user__firstname']) ?? null;
            $lastName = Validator::sanitize($_POST['user__lastname']) ?? null;
            $bio = Validator::sanitize($_POST['user__bio'] ?? null);
            $photo = $_POST['userPicture'];
    
            if ($userId && $firstName && $lastName) {
                $this->classprofille->updateProfile($userId, $firstName, $lastName, $bio, $photo);
                header("location: /setting/profile");
            } else {
                die("Données manquantes !");
            }
        }
    }
    


//    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (!isset($_POST['categoryId']) || !isset($_POST['updateCategoryName'])) {
//         var_dump($_POST['categoryId']);
//         die("Données invalides.");
//     }

//     $id = intval($_POST['categoryId']); 
//     $newName = htmlspecialchars(trim($_POST['updateCategoryName'])); 

//     if (empty($newName)) {
//         die("Le nom de la catégorie ne peut pas être vide.");
//     }
//     $this->categoryClass->updateCategory($this->connection, $id, $newName);
//     header('Location: /admin/categories');
//     exit();
// }
    
}