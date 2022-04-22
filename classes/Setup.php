<?php
// Include all classes
spl_autoload_register(function ($classname) {
    include "$classname.php";
});

// Report all errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize the database layer
$db = new mysqli(Config::$db["host"], Config::$db["user"], Config::$db["pass"], Config::$db["database"]);

// Setup for user table
echo "<p>Setting up user table\n</p>";
$db->query("drop table if exists User;");
$db->query("create table User (
    id int not null auto_increment,
    name text not null,
    email varchar(200) not null unique,
    password text not null,
    primary key (id)
);");
echo "User table set up!\n";

// Setup for composition table
echo "<p>Setting up composition table\n</p>";
$db->query("drop table if exists Composition;");
$db->query("create table Composition (
    id int not null auto_increment,
    name varchar(200) not null unique,
    composer_email text not null,
    location text not null,
    primary key (id)
);");
echo "Composition table set up!\n";

// Setup for user-composition table
echo "<p>Setting up UserComposition table\n</p>";
$db->query("drop table if exists UserComposition;");
$db->query("create table UserComposition (
    id int not null auto_increment,
    email text not null,
    name text not null,
    primary key (id)
);");
echo "<p>UserComposition table set up!\n</p>";

// Setup for recording table
echo "Setting up recording table\n";
$db->query("drop table if exists Recording;");
$db->query("create table Recording (
    id int not null auto_increment,
    name text not null,
    location text not null,
    author text not null,
    composition text not null,
    primary key (id)
);");
echo "<p>Recording table set up!\n</p>";

// Setup for product table
echo "Setting up Product table\n";
$db->query("drop table if exists Product;");
$db->query("create table Product (
    id int not null auto_increment,
    name text not null,
    location text not null,
    composition text not null,
    primary key (id)
);");
echo "<p>Product table set up!\n</p>";
