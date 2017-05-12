<?php
//print_r(PDO::getAvailableDrivers());
//print_r(Repair::getAdminRepairList());
// FRONT CONTROLLER

// 1. Общие настройки
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Kiev');
ini_set('max_file_uploads', "10");
session_start();

// 2. Подключение файлов системы
define('ROOT', dirname(__FILE__));
require_once(ROOT . '/components/Autoload.php');

// 4. Вызов Router
$router = new Router();
$router->run();
