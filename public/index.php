<?php 
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\EventController;
use App\Controllers\AppController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/event", EventController::class, "page");
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/404", AppController::class, "notFound");

$router->get("/register", AuthController::class, "register");
$router->get("/login", AuthController::class, "login");
$router->get("/forgetPassword", AuthController::class, "forgetPassword");
$router->get("/resetPassword", AuthController::class, "resetPassword");


$router->dispatch();
