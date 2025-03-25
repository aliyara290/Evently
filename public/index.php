<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config/Database.php";

use App\Core\Router;
use App\Core\Session;
use App\Middleware\AuthMiddleware;
use App\Controllers\Front\EventController;
use App\Controllers\Front\EventsController;
use App\Controllers\Front\SettingController;
use App\Controllers\Front\SponseurController;

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
use App\Controllers\back\TicketController;
use App\controllers\back\AppController as BackController;
use App\Controllers\Mail\MailController;
use App\Controllers\Front\PaymentController;

if (!Session::has("user")) {
    Session::set("user", [
        "active_role" => "guest"
    ]);
}
$router = new Router();

$router->get("/", HomeController::class, "home");
$router->get("/event", EventController::class, "page");
$router->get("/events", EventsController::class, "page");
$router->get("/events/search", EventsController::class, "searchForEvents");
$router->get("/events/city", EventsController::class, "searchForCity");
$router->get("/events/filter", EventsController::class, "filter");
// $router->get("/events", EventsController::class, "page");
$router->get("/setting/profile", SettingController::class, "profile", AuthMiddleware::class, ["Organizer", "Participant", "Admin"]);
$router->get("/setting/reset", SettingController::class, "setting", AuthMiddleware::class, ["Organizer", "Participant"]);
$router->get("/account/profile", UserProfileController::class, "page", AuthMiddleware::class, ["Organizer", "Participant"]);
$router->get("/faqs", AppController::class, "faqs");
// $router->get("/organizer/dashboard", OrganizerController::class, "page");
$router->get("/organizer/manage-user", AdminController::class, "Mange_user_page", AuthMiddleware::class, ["Organizer"]);
$router->get("/organizer/manage-events", OrganizerController::class, "Event_management_page", AuthMiddleware::class, ["Organizer"]);
$router->get("/organizer/create", CreateEventController::class, "page", AuthMiddleware::class, ["Organizer"]);
$router->get("/organizer/deleteEvent", OrganizerController::class, "deleteOrganizerEvent", AuthMiddleware::class, ["Organizer"]);
$router->get("/organizer/editEvent", OrganizerController::class, "editOrganizerEvent", AuthMiddleware::class, ["Organizer"]);
$router->post("/organizer/editEvent", OrganizerController::class, "updateEvent", AuthMiddleware::class, ["Organizer"]);
$router->get("/ticket", TicketController::class, "page");


// Auth
$router->get("/register", AuthController::class, "registerPage", AuthMiddleware::class, ["guest"]);
$router->post("/register", AuthController::class, "register");
$router->get("/login", AuthController::class, "loginPage", AuthMiddleware::class, ["guest"]);
$router->post("/login", AuthController::class, "login");
$router->get("/logout", AuthController::class, "logout");
$router->post("/organizer/create", EventsController::class, "create", AuthMiddleware::class, ["Organizer"]);

//$router->get("/login", AuthController::class, "login");
$router->get("/auth/google", AuthController::class, "googleLogin");
$router->get("/auth/google-callback", AuthController::class, "googleCallback");
$router->get("/forgetPassword", AuthController::class, "forgetPassword");
$router->get("/resetPassword", AuthController::class, "resetPassword");
// payments
$router->post("/payment/stripe", PaymentController::class, "createStripePayment");  // Handle Stripe payment
// $router->post("/payment/paypal", PaymentController::class, "createPayPalPayment");  // Handle PayPal payment
$router->get("/payment/confirm", PaymentController::class, "confirmPayment");
// Status code
$router->get("/forbidden", AppController::class, "forbidden");
$router->get("/404", AppController::class, "notFound");
$router->get("/payment/success", TicketController::class, "seccussPayment");
$router->get("/payment/failed", AppController::class, "paymentFailed");
// organizer router
$router->post("/Category", CategoryController::class, "create", AuthMiddleware::class, ["Admin"]);
$router->post("/Category/delete", CategoryController::class, "deleteCategory", AuthMiddleware::class, ["Admin"]);
$router->post("/Category/update", CategoryController::class, "updateCategory", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/Mange_user", AdminControllerBack::class, "getallUsers", AuthMiddleware::class, ["Admin"]);
$router->get("/active", AdminControllerBack::class, "UpduteStatustree");



$router->get("/delete", AdminControllerBack::class, "deleteUser");
// Admin routers
$router->get("/admin/dashboard", StasitickCountroler::class, "StatistickGlobale", AuthMiddleware::class, ["Admin"]);
// $router->get("/admin/categories", BackController::class, "categories");
$router->get("/admin/categories", CategoryController::class, "afficherCategories", AuthMiddleware::class, ["Admin"]);

// $router->get("/admin/updateCategories", BackController::class, "updateCategory");
$router->get("/admin/tags", BackController::class, "tags", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/tupdateTags", BackController::class, "updateTag", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/users", BackController::class, "users", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/events", BackController::class, "events", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/users", AdminControllerBack::class, "getallUsers", AuthMiddleware::class, ["Admin"]);

$router->get("/mange/delete", AdminControllerBack::class, "deleteUser", AuthMiddleware::class, ["Admin"]);
$router->get("/mange/update", AdminControllerBack::class, "UpduteStatus", AuthMiddleware::class, ["Admin"]);
$router->get("/mange/active", AdminControllerBack::class, "UpduteStatustree", AuthMiddleware::class, ["Admin"]);

// mange event admin
$router->get("/admin/events", AdminControllerBack::class, "getAllEvent", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/accept", AdminControllerBack::class, "updatestatus", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/refuse", AdminControllerBack::class, "updatestatusRefuse", AuthMiddleware::class, ["Admin"]);
$router->get("/admin/delete", AdminControllerBack::class, "deleteEvent", AuthMiddleware::class, ["Admin"]);
$router->post("/update-profile", SettingController::class, "updateProfile", AuthMiddleware::class, ["Participant", "Organizer"]);


// $router->get("/organizer/createsponser", OrganizerController::class, "PageCreteSponser", AuthMiddleware::class, ["Organizer"]);


$router->post("/Sponsoring", SponseurController::class, "create");

$router->post("/switch-role", AuthController::class, "switchRole");
$router->get("/event/teckte", EventController::class, "tecktepage");




$router->get("/organizer/sponser", SponseurController::class, "affichersponsorings", AuthMiddleware::class, ["Organizer"]);
$router->get("/organizer/createsponser", OrganizerController::class, "PageCreteSponser", AuthMiddleware::class, ["Organizer"]);
$router->post("/sponsor/delete", SponseurController::class, "deletesponsoring");
$router->post("/sponsor/updatepage", OrganizerController::class, "updatesponsoringpage");
$router->post("/Sponsoring/updte", SponseurController::class, "updatesponsoring");
$router->get('/get-cities-by-region', CreateEventController::class, 'getCitiesByRegion');


$router->dispatch();
