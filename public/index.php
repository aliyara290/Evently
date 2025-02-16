<?php 
session_start(); 
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
use App\Controllers\back\StasitickCountroler;
use App\controllers\back\AppController as BackController;
use App\Controllers\Mail\MailController;

$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/event", EventController::class, "page");
$router->get("/events", EventsController::class, "page");
$router->get("/events/search", EventsController::class, "searchForEvents");
$router->get("/events/city", EventsController::class, "searchForCity");
$router->get("/events/filter", EventsController::class, "filter");
// $router->get("/events", EventsController::class, "page");
$router->get("/setting/profile", SettingController::class, "profile");
$router->get("/setting/reset", SettingController::class, "setting");
$router->get("/account/profile", UserProfileController::class, "page");
$router->get("/faqs", AppController::class, "faqs");
$router->get("/organizer/dashboard", OrganizerController::class, "page");
$router->get("/admin/manage-user", AdminController::class, "Mange_user_page");
$router->get("/organizer/manage-events", OrganizerController::class, "Event_management_page");
$router->get("/organizer/create", CreateEventController::class, "page");
$router->get("/organizer/deleteEvent", OrganizerController::class, "deleteOrganizerEvent");
$router->get("/organizer/editEvent", OrganizerController::class, "editOrganizerEvent");
$router->post("/organizer/editEvent", OrganizerController::class, "updateEvent");

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
$router->post("/Category/delete",CategoryController::class, "deleteCategory");
$router->post("/Category/update",CategoryController::class, "updateCategory");
$router->get("/admin/Mange_user",AdminControllerBack::class, "getallUsers");
$router->get("/active",AdminControllerBack::class, "UpduteStatustree");



$router->get("/delete",AdminControllerBack::class, "deleteUser");
// Admin routers
$router->get("/admin/dashboard", BackController::class, "dashboard");
$router->get("/admin/dashboard", StasitickCountroler::class, "StatistickGlobale");
// $router->get("/admin/categories", BackController::class, "categories");
$router->get("/admin/categories", CategoryController::class, "afficherCategories");

// $router->get("/admin/updateCategories", BackController::class, "updateCategory");
$router->get("/admin/tags", BackController::class, "tags");
$router->get("/admin/tupdateTags", BackController::class, "updateTag");
$router->get("/admin/users", BackController::class, "users");
$router->get("/admin/events", BackController::class, "events");
$router->get("/admin/users", AdminControllerBack::class, "getallUsers");

$router->get("/mange/delete",AdminControllerBack::class, "deleteUser");
$router->get("/mange/update",AdminControllerBack::class, "UpduteStatus");
$router->get("/mange/active",AdminControllerBack::class, "UpduteStatustree");

// mange event admin
$router->get("/admin/events",AdminControllerBack::class,"getAllEvent");
$router->get("/admin/accept",AdminControllerBack::class,"updatestatus");
$router->get("/admin/refuse",AdminControllerBack::class,"updatestatusRefuse");
$router->get("/admin/delete",AdminControllerBack::class,"deleteEvent");
$router->post("/update-profile",SettingController::class,"updateProfile");
$router->get("/event/Reserver",EventController::class,"Resererpage");

//$router->get("/getTicket",MailController::class,"get");
$router->get("/getMail",MailController::class,"sendApprovedMail");

$router->dispatch();    