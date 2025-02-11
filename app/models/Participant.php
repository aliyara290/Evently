<?php 
namespace App\Models;
use App\Models\User;

class Participant extends User {
    public function __construct()
    {
        parent::__construct("participant");
    }
}