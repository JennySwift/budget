<?php

include("config.php");

session_start();

if ($_SERVER['SERVER_NAME'] != "localhost" && !isset($_SESSION['database_user_id'])) {
    //It's on the real web and they are not logged in.
    header("Location: php/login.php");
    exit;
}

if (!isset($_SESSION['database_user_id'])) {
    //They are not logged in.
    header("Location: $php/login.php");
    exit;
}

?>