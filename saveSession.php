<?php

include_once("ressource/include/fonction.php");

session_start();

$_SESSION[FB_TOKEN] = $_POST['accessToken'];

echo print_r($_POST,true);