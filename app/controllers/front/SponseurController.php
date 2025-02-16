<?php
namespace App\Controllers\Front;

use App\Models\Sponsoring;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Config\Database;

class SponseurController {
    protected $twig;
    protected $loader;
    private $connection;
    private $sponsorings;
    public function __construct(){
        $this->connection = Database::getInstance();
        if (!$this->connection) {
            die("Erreur de connexion à la base de données");
        }
        $this->sponsorings = new Sponsoring();
        $this->loader = new FilesystemLoader('C:\laragon\www\EVENTLY\app\views');
        $this->twig = new Environment($this->loader);
    }

    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['nameSponser']) || empty(trim($_POST['nameSponser']))) {
                die("Le nom de la catégorie ne peut pas être vide.");
            }
            $sponserName = htmlspecialchars(trim($_POST['nameSponser']));
            $sponserlogo = htmlspecialchars(trim($_POST['logo']));

          
            $this->sponsorings->createsponsorings($this->connection,$sponserName,$sponserlogo);
            header('location: /organizer/sponser');
        }
    }
    public function affichersponsorings() {
        $sponserData = $this->sponsorings->affichersponsorings($this->connection);
        echo  $this->twig->render('front/organizer/sponsoringMangmebt.twig', ['sponsorings' => $sponserData]);
    }

    public function deletesponsoring(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['sponsor_id']) && is_numeric($_POST['sponsor_id'])) {
                $id = intval($_POST['sponsor_id']); 
               
                $this->sponsorings->deletesponsorings($this->connection, $id);
                header("Location: /organizer/sponser"); 
                exit();
            } else {
                die("ID invalide ou manquant.");
            }
        } else {
            die("Méthode de requête non autorisée.");
        }
    }

    public function updatesponsoring() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['sponsor_id'])) {
                die("Données invalides.");
            }
    
            $id = intval($_POST['sponsor_id']); 
            $newName = htmlspecialchars(trim($_POST['nameSponser']));
            $newLogo = htmlspecialchars(trim($_POST['logo'])); ; 
    
            if (empty($newName)) {
                die("Le nom de la catégorie ne peut pas être vide.");
            }
            $this->sponsorings->updatesponsorings($this->connection, $id, $newName,$newLogo);
            header('Location: /organizer/sponser');
            exit();
        }
    }

}
