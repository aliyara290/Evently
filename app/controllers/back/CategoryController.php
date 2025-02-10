<?php
namespace App\Controllers\back;

use App\Models\Category;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Config\Database;

class CategoryController {
    protected $twig;
    protected $loader;
    private $connection;
    private $categoryClass;
    public function __construct(){
        $this->connection = Database::getInstance();
        if (!$this->connection) {
            die("Erreur de connexion à la base de données");
        }
        $this->categoryClass = new Category();
        $this->loader = new FilesystemLoader('C:\laragon\www\EVENTLY\app\views');
        $this->twig = new Environment($this->loader);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['category_name']) || empty(trim($_POST['category_name']))) {
                die("Le nom de la catégorie ne peut pas être vide.");
            }
            $categoryValue = htmlspecialchars(trim($_POST['category_name']));
          
            $this->categoryClass->createCategory($this->connection, $categoryValue);
            header('location: /organizer/category');
        }
    }
    public function afficherCategories() {
        $categoryData = $this->categoryClass->afficherCategories($this->connection);
        echo  $this->twig->render('front/organizer/Category.twig', ['categories' => $categoryData]);
    }
    

   
}
