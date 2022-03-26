<?php

spl_autoload_register(function ($classname) {
    include "$classname.php";
});

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = new mysqli(Config::$db["host"], Config::$db["user"], Config::$db["pass"], Config::$db["database"]);

$db->query("drop table if exists User;");
$db->query("create table User (
    id int not null auto_increment,
    email text not null,
    password text not null,
    primary key (id)
);");

$db->query("drop table if exists Composition;");
$db->query("create table Composition (
    id int not null auto_increment,
    user_id int not null,
    name text not null,
    primary key (id)
);");

$db->query("drop table if exists UserComposition;");
$db->query("create table UserComposition (
    id int not null auto_increment,
    user_id int not null,
    composition_id int not null,
    primary key (id)
);");

$db->query("drop table if exists Recording;");
$db->query("create table Recording (
    id int not null auto_increment,
    author_id int not null,
    composition_id int not null,
    primary key (id)
);");
