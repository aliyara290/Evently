<?php
namespace App\Controllers\back;


use App\Models\Category;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Config\Database;
use App\Models\User;
use App\Models\Event;


class StasitickCountroler {

    protected $twig;
    protected $loader;
    private $connection;
    private $get_class;
    private $classEvent;
    private $classcategory;


   public function __construct()
   {
    $this->connection = Database::getInstance();
    if (!$this->connection) {
        die("Erreur de connexion à la base de données");
    }
    $this->get_class = new User();
    $this->loader = new FilesystemLoader('C:\laragon\www\EVENTLY\app\views');
    $this->twig = new Environment($this->loader);
    $this->classEvent = new Event();
    $this->classcategory = new Category();



   }


    public function TotalEvents() {
        $Total = $this->classEvent->TotalEvent($this->connection);
        
        echo $this->twig->render('back/dashboard.twig', [
            'TotalEvents' => $Total
        ]);
    }
    public function TotalCategories() {
        $Total = $this->classcategory->TotalCategories($this->connection);
        echo $this->twig->render('back/dashboard.twig', [
            'TotalCategories' => $Total
        ]);
    }

    public function TotalEventsPending() {
        $Total = $this->classEvent->TotalEventpending($this->connection);
        
        echo $this->twig->render('back/dashboard.twig', [
            'TotalEventsPanding' => $Total
        ]); 
    }

    public function TotalEventsAccepted() {
        $Total = $this->classEvent->TotalEventaccepted($this->connection);
        
        echo $this->twig->render('back/dashboard.twig', [
            'TotalEventsAccepted' => $Total
        ]);
    }
    public function TotalUsers() {
        $Total = $this->get_class->TotalUser($this->connection);
        
        echo $this->twig->render('back/dashboard.twig', [
            'TotalUser' => $Total
        ]);
    }



    public function StatistickGlobale() {
        $TotalCategories = $this->classcategory->TotalCategories($this->connection);
        $TotalEvents = $this->classEvent->TotalEvent($this->connection);
        $TotalEventsPending = $this->classEvent->TotalEventpending($this->connection);
        $TotalEventsAccepted = $this->classEvent->TotalEventaccepted($this->connection);
        $TotalUsers = $this->get_class->TotalUser($this->connection);
        echo $this->twig->render('back/dashboard.twig', [
            'TotalCategories' => $TotalCategories,
            'TotalEvents' => $TotalEvents,
            'TotalEventsPending' => $TotalEventsPending,
            'TotalEventsAccepted' => $TotalEventsAccepted,
            'TotalUser' => $TotalUsers,
        ]);
    }
    
    
}