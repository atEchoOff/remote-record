<?php

class Controller
{
    private $command;

    private $utils;

    public function __construct($command)
    {
        $this->command = $command;
        $this->utils = new Utils();
    }

    public function run()
    {
        switch ($this->command) {
            case "login":
                $this->login();
                break;
            case "logout":
                $this->logout();
                break;
            case "home":
                $this->home();
                break;
            case "composition":
                $this->composition();
                break;
            case "new_composition":
                $this->new_composition();
                break;
            case "record":
                $this->record();
                break;
            default:
                $this->home();
        }
    }

    private function login()
    {
        if (isset($_POST) and isset($_POST["email"])) {
            // get user, dies if an error occurs
            $user = $this->utils->getUser($_POST["email"]);

            // no user exists, create user
            if ($user === false) {
                $this->utils->createUser($_POST["name"], $_POST["email"], $_POST["password"]);
                $_SESSION["email"] = $_POST["email"];
                $_SESSION["name"] = $_POST["name"];
                header("Location: ?command=home");
            } else {
                // user exists, check if password is correct
                if (password_verify($_POST["password"], $user["password"])) {
                    // password correct, log in!
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["name"] = $user["name"];
                    header("Location: ?command=home");
                } else {
                    // password incorrect, fail
                    echo "Login failed!";
                }
            }
        }
        include "templates/login.php";
    }

    private function logout()
    {
        unset($_SESSION["email"]);

        header("Location: ?command=login");
    }

    private function home()
    {
        $compositions = $this->utils->listCompositions();
        include "templates/home.php";
    }

    private function composition()
    {
        include "templates/composition.php";
    }

    private function new_composition()
    {
        // if they submitted a composition name
        if (isset($_POST) and isset($_POST["composition-name"])) {
            // create composition and redirect home
            $this->utils->createComposition($_POST["composition-name"]);
            header("Location: ?command=home");
        }
        include "templates/new_composition.php";
    }

    private function record()
    {
        include "templates/record.php";
    }
}
