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
    private $getcass;
    public function __construct(){
        $this->connection = Database::getInstance();
        if (!$this->connection) {
            die("Erreur de connexion à la base de données");
        }
        $this->getcass = new User();
        $this->loader = new FilesystemLoader('C:\laragon\www\EVENTLY\app\views');
        $this->twig = new Environment($this->loader);
    }

    public function getallUsers(){
        $usersData = $this->getcass->getAllUsers($this->connection);
        echo  $this->twig->render('front/admin/Mange_user.twig', ['users' => $usersData]);
    }
    public function UpduteStatus() {
        if (isset($_GET['id_active'])) {
            $id = (int) $_GET['id_active']; 
            
            $this->getcass->updateUser($id, $this->connection); 
            
            header('Location: /GestionUtilsateur'); 
            exit;
        } else {
            echo "no id!";
        }
    }
    
}