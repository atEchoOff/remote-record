<?php

// Import all classes
spl_autoload_register(function ($classname) {
    include "classes/$classname.php";
});

// Determine what command is queried
$command = "home";
if (isset($_GET["command"])) {
    $command = $_GET["command"];
}

// Sign into session
session_start();

// Run controller to display page
$controller = new Controller($command);
$controller->run();
