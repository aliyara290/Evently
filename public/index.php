<?php 
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\Front\EventController;
use App\Controllers\Front\EventsController;
use App\Controllers\Front\HomeController;
use App\Controllers\Front\OrganizerController;
use App\controllers\Front\AuthController;
use App\Controllers\Front\AppController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/event", EventController::class, "page");
$router->get("/events", EventsController::class, "page");
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/faqs", AppController::class, "faqs");
$router->get("/404", AppController::class, "notFound");
$router->get("/register", AuthController::class, "register");
$router->get("/organizer/dashboard", OrganizerController::class, "page");
$router->get("/organizer/category", OrganizerController::class, "Category_page");
$router->get("/organizer/Mange_user", OrganizerController::class, "Mange_user_page");
$router->get("/organizer/Event_management", OrganizerController::class, "Event_management_page");

$router->get("/login", AuthController::class, "login");
$router->get("/forgetPassword", AuthController::class, "forgetPassword");
$router->get("/resetPassword", AuthController::class, "resetPassword");
 


$router->dispatch();
