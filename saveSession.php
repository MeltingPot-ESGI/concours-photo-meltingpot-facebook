<?php

include_once("ressource/include/fonction.php");

session_start();

$_SESSION[FB_TOKEN] = $_POST['accessToken'];

if ($_POST['is_participate']) {
    $_SESSION['is_participate'] = 1;
}

echo print_r($_POST,true);