<?php

//Общие настройки
$config = require_once (ROOT . '/config/app.php');

if($config['debug'] == true) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(null);
}
date_default_timezone_set($config['timezone']);
ini_set('max_file_uploads', "10");
session_start();


// Подключение файлов системы
require_once(ROOT . '/vendor/autoload.php');


//Static Environment Definition
if($config['env'] == 'local') {
    $env = __DIR__ . '/../.env.local';
} elseif ($config['env'] == 'production') {
    $env = __DIR__ . '/../.env';
}

josegonzalez\Dotenv\Loader::load([
    'filepath'  => $env,
    'toEnv'     => true
]);

//Вызов Router
require_once(ROOT . '/components/Router.php');
$router = new Umbrella\components\Router();
$router->run();