<?php

/**
 * Handles requests for pages
 */
class Controller
{
    /**
     * The given command of the current request
     */
    private $command;

    /**
     * Handles menial tasks for the controller (such as database queries)
     */
    private $utils;

    /**
     * Constructs a controller given the specified command
     * initializes the utility manager
     */
    public function __construct($command)
    {
        $this->command = $command;
        $this->utils = new Utils();
    }

    /**
     * Runs the given request
     */
    public function run()
    {
        switch ($this->command) {
            case "login":
                $this->login();
                break;
            case "signup":
                $this->signup();
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
            case "delete":
                $this->delete();
                break;
            case "stitch_audio":
                $this->stitch_audio();
                break;
            default:
                $this->home();
        }
    }

    /**
     * Login page (also has a link to sign up)
     * Takes email and password and verifies user
     */
    private function login()
    {
        if (isset($_POST) and isset($_POST["email"])) {
            // get user, dies if an error occurs
            $user = $this->utils->getUser($_POST["email"]);

            // no user exists, create user
            if ($user === false) {
                echo "FIXME: user does not exist";
            } else {
                // user exists, check if password is correct
                if (password_verify($_POST["password"], $user["password"])) {
                    // password correct, log in!
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["name"] = $user["name"];
                    header("Location: ?command=home");
                } else {
                    // password incorrect, fail
                    echo "FIXME: Login failed!";
                }
            }
        }
        include "templates/login.php";
    }

    /**
     * Signup page (also has a link to login)
     * Takes name, email, and password
     * Creates user, and sets name
     */
    private function signup()
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
                echo "FIXME: User already exists";
            }
        }
        include "templates/signup.php";
    }

    /**
     * Clears the session of the user and redirects to the login page
     */
    private function logout()
    {
        unset($_SESSION["email"]);
        unset($_SESSION["name"]);

        header("Location: ?command=login");
    }

    /**
     * List of all compositions which the user is a member of
     */
    private function home()
    {
        $compositions = $this->utils->listCompositions();
        include "templates/home.php";
    }

    /**
     * Composition page
     * Contains an edit page where composers can edit the placing of all tracks for the composition
     * Can be saved by composer
     * Shows all saved stitched recordings
     */
    private function composition()
    {
        // Get composition and all of its recordings
        $composition = $this->utils->getComposition($_GET["composition"]);
        $recordings = $this->utils->getCompositionRecordings($composition);
        include "templates/composition.php";
    }

    /**
     * Page to create a new composition
     * Initalizes a new composition for the user
     * Saves the backtrack for the composition
     */
    private function new_composition()
    {
        // if they submitted a composition name
        if (isset($_POST) and isset($_POST["composition-name"])) {
            // Save the given backtrack
            $info = pathinfo($_FILES['backtrack']['name']);
            $ext = $info['extension'];
            $newname = $_POST["composition-name"] . "." . $ext;

            // Save the backtrack into the audio directory
            $target = 'audio/' . $newname;
            move_uploaded_file($_FILES['backtrack']['tmp_name'], $target);

            // create composition and redirect home
            $this->utils->createComposition($_POST["composition-name"], $target);
            header("Location: ?command=home");
        }
        include "templates/new_composition.php";
    }

    /**
     * Record page for a specific composition
     * User can record self along with backtrack
     * User can save and listen to multiple recordings of themself for the composition
     */
    private function record()
    {
        $composition = $this->utils->getComposition($_GET["composition"]);

        if (isset($_POST) and isset($_POST["record"])) {
            // Convert string audio data into a binary array
            // https://stackoverflow.com/questions/9620805/save-byte-array-to-a-file-php
            $data = explode(",", $_POST["record"]);
            $bin_data = pack('C*', ...$data);

            // Determine name for audio file
            $newname = $composition["name"] . "-" . rand(1, 1000000000) . rand(1, 1000000000) . rand(1, 1000000000) . rand(1, 1000000000) . ".wav";

            // Determine path and put byte data into that path
            $target = 'audio/' . $newname;
            file_put_contents($target, $bin_data);

            // create composition and redirect home
            $this->utils->createUserCompositionRecording($_POST["name"], $composition["name"], $target);
        }

        $recordings = $this->utils->getUserCompositionRecordings($composition);


        include "templates/record.php";
    }

    /**
     * Delete a composition recording
     * Allow delete only if:
     *      User is the owner of the file
     *      User is the composer of the composition owning this file
     * This only deletes the file if it is owned by the logged-in user
     */
    private function delete()
    {
        $recording = $this->utils->getRecording($_GET["id"]);

        // If there is a recording and the recording belongs to the current user, then we can delete
        // We also allow a delete if the current user is the composer owning this audio
        if ($recording !== false and (($recording['author'] === $_SESSION["email"]) or ($this->utils->getComposition($recording['composition'])["composer_email"] === $_SESSION["email"]))) {
            $this->utils->deleteRecording($_GET["id"]);
        }

        // redirect to previous location
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Stitch audio together from ids list and given delays
     */
    private function stitch_audio()
    {
        echo $_GET["ids"] . "\n" . $_GET["margins"];
    }
}
