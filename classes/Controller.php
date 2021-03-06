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
            case "join_composition":
                $this->join_composition();
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
            case "save_merge":
                $this->save_merge();
                break;
            case "get_new_composition_json":
                $this->get_new_composition_json();
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
        // Initialize errors as nothing
        // Assume nothing was incorrect
        $nameError = "";
        $emailError = "";
        $passwordError = "";
        if (isset($_POST) and isset($_POST["email"])) {
            // get user, dies if an error occurs
            $user = $this->utils->getUser($_POST["email"]);

            // no user exists, create user
            if ($user === false) {
                $emailError = " * A user with this email does not exist";
            } else {
                // user exists, check if password is correct
                if (password_verify($_POST["password"], $user["password"])) {
                    // password correct, log in!
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["name"] = $user["name"];
                    header("Location: ?command=home");
                } else {
                    // password incorrect, fail
                    $passwordError = " * Incorrect password";
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
        // Initialize errors as nothing
        // Assume nothing was incorrect
        $nameError = "";
        $emailError = "";
        $passwordError = "";
        //validate email is set
        if (isset($_POST) and isset($_POST["email"]) and isset($_POST["name"]) and isset($_POST["password"]) and !empty($_POST["email"]) and !empty($_POST["name"]) and !empty($_POST["password"])) {

            // get user, dies if an error occurs
            $user = $this->utils->getUser($_POST["email"]);

            // no user exists, create user
            if ($user === false) {
                // validate email with regex
                $emailRegex = "/^(([a-zA-Z0-9\+\-_])+(\.(([a-zA-Z0-9\+\-_])+))*)@(([A-Za-z0-9\-])+(\.(([A-Za-z0-9\-])+))+)$/";
                if (preg_match($emailRegex, $_POST["email"])) {
                    $this->utils->createUser($_POST["name"], $_POST["email"], $_POST["password"]);
                    $_SESSION["email"] = $_POST["email"];
                    $_SESSION["name"] = $_POST["name"];
                    header("Location: ?command=home");
                } else {
                    $emailError = " * Invalid email address";
                }
            } else {
                // user exists, check if password is correct
                $emailError = " * A user with this email already exists";
            }
            // error checking for empty fields (might require the user to edit the html to get here, but here for safety anyways)
        } else if (isset($_POST["email"]) and empty($_POST["email"])) {
            $emailError = " * email field is required.";
        } else if (isset($_POST["name"]) and empty($_POST["name"])) {
            $nameError = " * name field is required.";
        } else if (isset($_POST["password"]) and empty($_POST["password"])) {
            $passwordError = " * password field is required.";
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

        // Get all composition products
        $products = $this->utils->getCompositionProducts($_GET["composition"]);

        // Get the current user
        $user = $this->utils->getUser($_SESSION["email"]);

        // if the current user is not the owner of the composition page
        // then redirect to the record page
        if ($_SESSION["email"] !== $composition["composer_email"]) {
            header("Location: ?command=record&composition={$composition['name']}");
        }

        $recordings = $this->utils->getCompositionRecordings($composition);
        include "templates/composition.php";
    }

    /**
     * Page to join a composition
     * Looks like home page, but each composition link links to add a composition
     */
    private function join_composition()
    {
        // if a composition to join is included, join it
        if (isset($_GET["composition"])) {
            if ($this->utils->memberOfComposition($_GET["composition"]) === false) {
                $this->utils->createUserComposition($_GET["composition"]);
            }

            // Redirect back to join page
            header("Location: ?command=join_composition");
        }
        $compositions = $this->utils->allForeignCompositions();
        include "templates/join_composition.php";
    }

    /**
     * Page to create a new composition
     * Initalizes a new composition for the user
     * Saves the backtrack for the composition
     */
    private function new_composition()
    {
        // check if they submitted a composition name
        $compositionError = "";
        if (isset($_POST) and isset($_POST["composition-name"]) and !empty($_POST["composition-name"])) {
            if (Utils::isAlphaNumericOrSpace($_POST["composition-name"]) === true) {
                if ($this->utils->getComposition($_POST["composition-name"]) === false) {
                    if ($_FILES['backtrack']['size'] < 1900000) {
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
                    } else {
                        $compositionError = " * The file must be 1.9MB or smaller";
                    }
                } else {
                    $compositionError = " * There is already a composition with this name";
                }
            } else {
                $compositionError = " * Composition name must be alphanumeric or space";
            }
        } else if (isset($_POST["composition-name"]) and empty($_POST["composition-name"])) {
            $compositionError = " * Must enter a composition name";
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

        // if the current user is not a member of this composition, redirect home
        if ($composition === false or $this->utils->memberOfComposition($composition["name"]) === false) {
            header("Location: ?command=home");
        }

        if (isset($_POST) and isset($_POST["record"]) and !empty($_POST["record"])) {
            // Convert string audio data into a binary array
            // https://stackoverflow.com/questions/9620805/save-byte-array-to-a-file-php
            $data = explode(",", $_POST["record"]);
            $bin_data = pack('C*', ...$data);

            // Get an ID for the new recording
            $id = ($this->utils->getNextRecordingID());

            // Determine name for audio file
            $newname = $id . ".webm";

            // Determine path and put byte data into that path
            $target = 'audio/' . $newname;
            file_put_contents($target, $bin_data);

            // create composition and redirect home
            $this->utils->createUserCompositionRecording($id, $_POST["name"], $composition["name"], $target);
        }

        $recordings = $this->utils->getUserCompositionRecordings($composition);


        include "templates/record.php";
    }

    /**
     * Delete a composition recording or product
     * If it is a product, only allow delete if
     *      User is the composer of the composition owning this file
     * Allow delete only if:
     *      User is the owner of the file
     *      User is the composer of the composition owning this file
     * This only deletes the file if it is owned by the logged-in user
     */
    private function delete()
    {
        // If this is a product, then the behaviour changes
        if ($_GET["product"] === "1") {
            // Get the product
            $product = $this->utils->getProduct($_GET["id"]);

            // Only delete if user is owner of this composition
            if ($product !== false and ($this->utils->getComposition($product['composition'])["composer_email"] === $_SESSION["email"])) {
                // Valid user, delete the product
                $this->utils->deleteProduct($_GET["id"]);
            }
        } else {
            // Not a product, this is a composition recording
            $recording = $this->utils->getRecording($_GET["id"]);

            // If there is a recording and the recording belongs to the current user, then we can delete
            // We also allow a delete if the current user is the composer owning this audio
            if ($recording !== false and (($recording['author'] === $_SESSION["email"]) or ($this->utils->getComposition($recording['composition'])["composer_email"] === $_SESSION["email"]))) {
                $this->utils->deleteRecording($_GET["id"]);
            }
        }

        // redirect to previous location
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Stitch audio together from ids list and given delays
     */
    private function stitch_audio()
    {
        // Get the file location and new width from merging together all tracks under each id
        // And given each margin
        $results = $this->utils->mergeAudio(explode(",", $_GET["ids"]), explode(",", $_GET["margins"]));

        $file_location = $results[0];
        $new_width = $results[1];

        // Show the playable waveform (will appear on composition page)
        echo Builder::playableWaveform($file_location, "", null, false, false, $new_width);
    }

    /**
     * Temporary page that permanently saves a merged composition if the current user is a composer for the specified composition
     * Given: ids
     * Given: margins
     * Given: composition name ("composition")
     * Given: name of merge ("name")
     */
    private function save_merge()
    {
        // Make sure current user is the admin of the requested composition
        if ($_SESSION["email"] === $this->utils->getComposition($_GET["composition"])["composer_email"]) {
            // Merge audio, save results
            $this->utils->mergeAudio(explode(",", $_GET["ids"]), explode(",", $_GET["margins"]), $_GET["composition"], $_GET["name"]);
        }

        // Redirect to previous page
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    /*
     save json of the new compositions into a variable
     based on the requirement "Implement at least one query that returns JSON instead of HTML" - will use this next sprint to update the new composition table probably
     (ie, reload the list using ajax)
    */
    public function get_new_composition_json()
    {
        header('Content-Type: application/json; charset=utf-8');
        $temp = $this->utils->allForeignCompositions();
        $compositions = json_encode($temp);
        echo $compositions;
    }
}
