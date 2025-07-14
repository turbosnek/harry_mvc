<?php

require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';
require_once 'app/core/Url.php';
session_start();

$path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
$path = str_replace("index.php", "", $path);

define('ROOT', $path);
define('ASSETS', $path . "public/assets/");

$app = new App();