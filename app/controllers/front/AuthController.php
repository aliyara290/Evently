<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

use Google_Client;
use Google\Service\Oauth2;

class AuthController
{
    private $userModel;
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId('523106584100-hrtf77h34cd7hfsau27vislklbu7mfea.apps.googleusercontent.com');
        $this->client->setClientSecret('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');
        $this->client->setRedirectUri('http://localhost:8080/auth/google-callback');
        $this->client->addScope('email');
        $this->client->addScope('profile');
        $this->userModel = new User();
    }

    public function googleLogin()
    {
        $authUrl = $this->client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();
    }

    public function googleCallback()
    {
        if (!isset($_GET['code'])) {
            die('Authorization code not received');
        }

        $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
        $token = $this->client->getAccessToken();
        $this->client->setAccessToken($token);

        $oauth = new Oauth2($this->client);
        $googleUser = $oauth->userinfo->get();

        $user = $this->userModel->findByEmail($googleUser->email);

        if (!$user) {
            $this->userModel->setFirstName($googleUser->givenName);
            $this->userModel->setLastName($googleUser->familyName);
            $this->userModel->setEmail($googleUser->email);
            $this->userModel->setGoogleId($googleUser->id);
            $this->userModel->setAvatar($googleUser->picture);
            $this->userModel->register();
            echo "yes";
        } else echo "no";

        $getUserId = $this->userModel->findByEmail($googleUser->email);
        $id =  $getUserId[0]["id"];
        $roles = $this->userModel->findRole($id);
        Session::set("user", [
            "id" => $getUserId[0]["id"],
            "email" => $getUserId[0]["email"],
            "firstName" => $getUserId[0]["firstname"],
            "lastName" => $getUserId[0]["lastname"],
            "avatar" => $getUserId[0]["avatar"],
            "roles" => $roles,
            "active_role" => $getUserId[0]["id"] === 10 ? "Admin" : $roles[0]["name"]
        ]);
        header('Location: /');
        exit();
    }

    public function registerPage()
    {
        view::render("register");
    }

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $firstName = Validator::sanitize($_POST["u_firstname"]);
            $lastName = Validator::sanitize($_POST["u_lastname"]);
            $email = Validator::validateEmail($_POST["u_email"]);
            $password = Validator::validatePassword($_POST["u_password"]);
            $this->userModel->setFirstName($firstName);
            $this->userModel->setLastName($lastName);
            $this->userModel->setEmail($email);
            $this->userModel->setPassword($password);
            $check = $this->userModel->register();
            if ($check) {
                header("location: /login");
            }
        }
    }

    public function loginPage()
    {
        view::render("login");
    }
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = Validator::validateEmail($_POST["u_email"]);
            $password = Validator::validatePassword($_POST["u_password"]);
            $this->userModel->setEmail($email);
            $this->userModel->setPassword($password);
            $check = $this->userModel->login();
            $user = $this->userModel->findByEmail($_POST["u_email"]);

            if ($check) {
                $roles = $this->userModel->findRole($user[0]["id"]);
                Session::set("user", [
                    "id" => $check["id"],
                    "email" => $check["email"],
                    "firstName" => $check["firstname"],
                    "lastName" => $check["lastname"],
                    "avatar" => $check["avatar"],
                    "roles" => $roles,
                    "active_role" => $check["id"] === 10 ? "Admin" : $roles[0]["name"]
                ]);
                header("location: /");

                exit();
            }
        }
    }
    public function forgetPassword()
    {
        view::render("forgetPassword");
    }

    public function resetPassword()
    {
        view::render("resetPassword");
    }

    public function logout()
    {
        Session::destroy();
        Session::remove("user");
        Session::set("user", [
            "active_role" => "guest"
        ]);
        header("location: /login");
        exit;
    }
    public function switchRole()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["role"])) {
            $newRole = $_POST["role"];

            // Get the roles from the session
            $roles = Session::get("user")["roles"];

            // Find the role in the session
            foreach ($roles as $role) {
                if ($role["name"] === $newRole) {
                    // Update the active role in the session
                    $_SESSION["user"]["active_role"] = $role["name"];  // Store the role's name as a string
                    break;
                }
            }
        }

        // Redirect to the homepage (or wherever you want the user to go after switching roles)
        header("Location: /");
        exit();
    }
}
