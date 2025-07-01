<?php

require_once "../app/core/App.php";
require_once "../app/core/Controller.php";
require_once "../app/core/Database.php";
require_once "../app/core/Url.php";

session_start();

spl_autoload_register(function($class) {
    $paths = '../app/core' . $class . '.php';

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$path = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
$path = str_replace("index.php", "", $path);

define('ROOT', $path);
define('ASSETS', $path . "assets/");

$app = new App();