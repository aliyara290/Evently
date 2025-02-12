<?php 
session_start();
// session_destroy();   
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Controllers\Front\EventController;
use App\Controllers\Front\EventsController;
use App\Controllers\Front\SettingController;
use App\Controllers\Front\UserProfileController;
use App\Controllers\Front\CreateEventController;
use App\Controllers\Front\HomeController;
use App\Controllers\Front\OrganizerController;
use App\controllers\Front\AuthController;
use App\Controllers\Front\AppController;
use App\Controllers\Front\AdminController;
use App\controllers\back\AdminControllerBack;
use App\controllers\back\CategoryController;
use App\controllers\back\AppController as BackController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/event", EventController::class, "page");
$router->get("/events", EventsController::class, "page");
$router->get("/setting/profile", SettingController::class, "profile");
$router->get("/setting/reset", SettingController::class, "setting");
$router->get("/account/profile", UserProfileController::class, "page");
$router->get("/faqs", AppController::class, "faqs");
$router->get("/organizer/dashboard", OrganizerController::class, "page");
$router->get("/admin/category", AdminController::class, "Category_page");
$router->get("/admin/Mange_user", AdminController::class, "Mange_user_page");
$router->get("/organizer/Event_management", OrganizerController::class, "Event_management_page");
$router->get("/organizer/create", CreateEventController::class, "page");
// Auth
$router->get("/register", AuthController::class, "registerPage");
$router->post("/register", AuthController::class, "register");
$router->get("/login", AuthController::class, "loginPage");
$router->post("/login", AuthController::class, "login");
$router->get("/logout", AuthController::class, "logout");
$router->post("/organizer/create", EventsController::class, "create");

//$router->get("/login", AuthController::class, "login");
$router->get("/auth/google", AuthController::class, "googleLogin");
$router->get("/auth/google-callback", AuthController::class, "googleCallback");
$router->get("/forgetPassword", AuthController::class, "forgetPassword");
$router->get("/resetPassword", AuthController::class, "resetPassword");
// Status code
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/404", AppController::class, "notFound");
// organizer router
$router->post("/Category",CategoryController::class, "create");
$router->get("/admin/category", CategoryController::class, "afficherCategories");
$router->post("/Category/delete",CategoryController::class, "deleteCategory");
$router->post("/category/update",CategoryController::class, "updateCategory");
$router->get("/admin/Mange_user",AdminControllerBack::class, "getallUsers");
$router->get("/block",AdminControllerBack::class, "UpduteStatus");
$router->get("/active",AdminControllerBack::class, "UpduteStatustree");
$router->get("/delete",AdminControllerBack::class, "deleteUser");
// Admin routers
$router->get("/admin/dashboard", BackController::class, "dashboard");
$router->get("/admin/categories", BackController::class, "categories");
$router->get("/admin/updateCategories", BackController::class, "updateCategory");
$router->get("/admin/tags", BackController::class, "tags");
$router->get("/admin/tupdateTags", BackController::class, "updateTag");
$router->get("/admin/users", BackController::class, "users");
$router->get("/admin/events", BackController::class, "events");






$router->dispatch();
