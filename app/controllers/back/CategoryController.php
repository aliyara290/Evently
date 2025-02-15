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
            if (!isset($_POST['categoryName']) || empty(trim($_POST['categoryName']))) {
                die("Le nom de la catégorie ne peut pas être vide.");
            }
            $categoryValue = htmlspecialchars(trim($_POST['categoryName']));
          
            $this->categoryClass->createCategory($this->connection, $categoryValue);
            header('location: /admin/categories');
        }
    }
    public function afficherCategories() {
        $categoryData = $this->categoryClass->afficherCategories($this->connection);
        echo  $this->twig->render('back/categories.twig', ['categories' => $categoryData]);
    }

    public function deleteCategory(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
                $id = intval($_POST['category_id']); 
               
                $this->categoryClass->deleteCategory($this->connection, $id);
                header("Location: /admin/categories"); 
                exit();
            } else {
                die("ID invalide ou manquant.");
            }
        } else {
            die("Méthode de requête non autorisée.");
        }
    }

    public function updateCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['categoryId']) || !isset($_POST['updateCategoryName'])) {
                var_dump($_POST['categoryId']);
                die("Données invalides.");
            }
    
            $id = intval($_POST['categoryId']); 
            $newName = htmlspecialchars(trim($_POST['updateCategoryName'])); 
    
            if (empty($newName)) {
                die("Le nom de la catégorie ne peut pas être vide.");
            }
            $this->categoryClass->updateCategory($this->connection, $id, $newName);
            header('Location: /admin/categories');
            exit();
        }
    }

    
    

    
    
}
