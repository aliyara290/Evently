<?php 
namespace App\Controllers\back;

use App\Controllers\Mail\MailController;
use App\Models\Category;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Config\Database;
use App\Models\User;
use App\Models\Event;

class AdminControllerBack {
    protected $twig;
    protected $loader;
    private $connection;
    private $get_class;
    private $mail;
    private $classEvent;
    private $role = 'Participant';
    public function __construct(){
        $this->connection = Database::getInstance();
        if (!$this->connection) {
            die("Erreur de connexion à la base de données");
        }
        $this->get_class = new User();
        $this->loader = new FilesystemLoader('C:\laragon\www\EVENTLY\app\views');
        $this->twig = new Environment($this->loader);
        $this->classEvent = new Event();
        $this->mail = new MailController();
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
            $this->get_class->deleteUser($this->connection,$id);
            header('Location: /admin/Mange_user'); 
            exit; 
        }else{
            echo "id no corct";
        }
    }

    public function getAllEvent()
    {
        $events = $this->classEvent->redAllEventsPending($this->connection);
        $eventsActive = $this->classEvent->redAllEventsActive($this->connection); 
        echo $this->twig->render('back/manageEvents.twig', [
            'events' => $events,
            'events_active' => $eventsActive
        ]);

    }
    
    public function getAllEventActive (){
        $usersData = $this->classEvent->redAllEventsActive($this->connection);
        echo  $this->twig->render('back/manageEvents.twig', ['events_active' => $usersData]);
    }
    

    public function updatestatus(){
        if(isset($_GET['eventId'])) {
            $check = $this->classEvent->updateStatusEvent($_GET['eventId'],$this->connection);
            if($check) {
                $this->mail->sendApprovedMail();
            }
            header('Location: /admin/events'); 
        }else{
            echo "id no corict";
        }
    }
    public function updatestatusRefuse(){
        if(isset($_GET['eventId'])) {
            $this->classEvent->updateStatusEventRrefuse($_GET['eventId'],$this->connection);
            header('Location: /admin/events'); 

        }else{
            echo "id no corict";
        }
    }

    public function deleteEvent(){
        if(isset($_GET['eventId'])){
            $id = (int) $_GET['eventId'];
            var_dump($id);
            $this->classEvent->deleteEventId($id);
            header('Location: /admin/events'); 
            exit; 
        }else{
            echo "id no corct";
        }
    }

   
}    


// if (isset($_GET['UserId'])) {
//     $id = (int) $_GET['UserId'];
//     var_dump("hello") ;
//     $this->get_class->updateUser($id, $this->connection); 
//     exit;
// } else {
//     echo "no id!";
// }