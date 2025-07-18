<?php

// Nastaví pracovní adresář na root projektu (ne na složku public)
chdir(dirname(__DIR__));

require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';
require_once 'app/core/Url.php';

session_start();

// Dynamická detekce protokolu (http nebo https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

// Doména (např. localhost nebo mojedomena.cz)
$host = $_SERVER['HTTP_HOST'];

// Cesta k aplikaci (např. /harry_mvc nebo prázdná při rootu domény)
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = preg_replace('@/public$@', '', $scriptName);

// ROOT: https://localhost/harry_mvc
define('ROOT', rtrim($protocol . $host . $basePath, '/'));

// ASSETS: https://localhost/harry_mvc/assets
define('ASSETS', ROOT . '/assets/');

$app = new App();