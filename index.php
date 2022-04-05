<?php

/**
 * Sources used:
 *      - https://web.dev/media-recording-audio/
 *      - https://stackoverflow.com/questions/9620805/save-byte-array-to-a-file-php
 *      - https://stackoverflow.com/questions/37134433/convert-input-file-to-byte-array
 *      - http://wavesurfer-js.org/docs/
 *      - https://www.w3schools.com/howto/howto_js_draggable.asp
 *      - https://stackoverflow.com/questions/1996122/how-to-prevent-xss-with-html-php
 *      - https://stackoverflow.com/questions/18618865/load-part-page-with-jquery-ajax
 *      - https://www.geekstrick.com/form-validation-using-php-and-regex/
 *      - https://stackoverflow.com/questions/895171/prevent-users-from-submitting-a-form-by-hitting-enter
 *      - https://www.mariadbtutorial.com/mariadb-aggregate-functions/mariadb-count/#:~:text=The%20MariaDB%20count()%20is,of%20rows%20in%20a%20table.&text=The%20count()%20function%20accepts,non%2Dnull%20values%20including%20duplicates.
 * 
 */
// Import all classes
spl_autoload_register(function ($classname) {
    include "classes/$classname.php";
});

// Determine what command is queried
$command = "login";
if (isset($_GET["command"])) {
    $command = $_GET["command"];
}

// Sign into session
session_start();

// If the user is not signing up and they are not logged in, go to login page
if (!($command === "signup") && !isset($_SESSION["email"])) {
    $command = "login";
}

// Get rid of any illegal characters in post to avoid XSS
foreach ($_POST as $key => $val) {
    $_POST[$key] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

// Run controller to display page
$controller = new Controller($command);
$controller->run();
