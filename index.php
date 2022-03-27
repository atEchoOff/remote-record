<?php

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

if (!($command === "signup") && !isset($_SESSION["email"])) {
    $command = "login";
}

// Run controller to display page
$controller = new Controller($command);
$controller->run();
