<?php

// Nastaví pracovní adresář na root projektu (ne na složku public)
chdir(dirname(__DIR__));

require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';
require_once 'app/core/Url.php';

session_start();

// Detekce protokolu
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

// Doména
$host = $_SERVER['HTTP_HOST'];

// Cesta ke kořeni projektu
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = preg_replace('@/public$@', '', $scriptName);

// Definice ROOT bez trailing slash (např. http://localhost/harry_mvc)
define('ROOT', rtrim($protocol . $host . $basePath, '/'));

// Definice ASSETS s /assets (např. http://localhost/harry_mvc/assets)
define('ASSETS', ROOT . '/assets/');

$app = new App();