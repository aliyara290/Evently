<?php 
namespace App\Controllers\back;
use App\Models\Category;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Config\Database;
use App\Models\User;
class AdminControllerBack {
    protected $twig;
    protected $loader;
    private $connection;
    private $get_class;
    private $role = 'Participant';
    public function __construct(){
        $this->connection = Database::getInstance();
        if (!$this->connection) {
            die("Erreur de connexion à la base de données");
        }
        $this->get_class = new User();
        $this->loader = new FilesystemLoader('C:\laragon\www\EVENTLY\app\views');
        $this->twig = new Environment($this->loader);
    }

    public function getallUsers(){
        $usersData = $this->get_class->getAllUsers($this->connection);
    
        echo $this->twig->render('back/users.twig', [
            'usersActive' => $usersData['active'], 
            'usersSuspended' => $usersData['suspended']
        ]);
    }
    

    public function UpduteStatus() {
        if (isset($_GET['UserId'])) {
            $id = (int) $_GET['UserId'];
            var_dump("hello") ;
            $this->get_class->updateUser($id, $this->connection); 
            header('Location: /admin/users'); 
            exit;
        } else {
            echo "no id!";
        }
    }
    public function UpduteStatustree() {
        if (isset($_GET['UserId'])) {
            $id = (int) $_GET['UserId']; 
            $this->get_class->updateUserToActive($id, $this->connection); 
            header('Location: /admin/Mange_user'); 
            exit;
        } else {
            echo "id no courct";
        }
    }
    
    public function deleteUser(){
        if(isset($_GET['UserId'])){
            $id = (int) $_GET['UserId'];
            var_dump($id);
            $this->get_class->deleteUser($this->connection,$id);
            header('Location: /admin/Mange_user'); 
            exit; 
        }else{
            echo "id no corct";
        }
    }
    
}