<?php

class Controller
{
    private $command;

    private $db;

    public function __construct($command)
    {
        $this->command = $command;
        $this->db = new Database();
    }

    public function run()
    {
        switch ($this->command) {
            case "login":
                $this->login();
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
            // check if user exists
            if (($val = $this->db->query("select * from user where email = ?", "s", $_POST["email"])) !== false) {
                // user exists
                $user_exists = false;

                // check password
                foreach ($val as $user) {
                    if (password_verify($_POST["password"], $user["password"])) {
                        $user_exists = true;
                        break;
                    }
                }

                // username and password correct
                if (sizeof($val) === 1 and $user_exists) {
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["id"] = $user["id"];

                    header("Location: ?command=home");
                }
                // username does not exist
                else if (sizeof($val) === 0) {
                    // create account
                    $_SESSION["email"] = $_POST["email"];
                    $this->db->query("insert into user (email, password) values (?, ?);", "ss", $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                } else {
                    // username does not exist, make account
                    echo "Could not log in, incorrect username!";
                }
            } else {
                echo "Error taking statement, please report this to the developer";
            }
        }
        include "templates/login.php";
    }

    private function home()
    {
        include "templates/home.php";
    }

    private function composition()
    {
        include "templates/composition.php";
    }

    private function new_composition()
    {
        include "templates/new_composition.php";
    }

    private function record()
    {
        include "templates/record.php";
    }
}
