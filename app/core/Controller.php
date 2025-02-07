<?php 
namespace App\Core;

class Controller {
    public function model($model)
    {
        $modelPath = "../app/models/" . ucfirst($model) . ".php";

        if (file_exists($modelPath)) {
            require_once $modelPath;
            $modelClass = "App\\Models\\" . ucfirst($model);
            return new $modelClass();
        }

        die("Model '$model' not found.");
    }
    
    public static function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
}