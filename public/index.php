<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
// Habilitar el manejo de errores durante el desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir rutas principales
chdir(dirname(__DIR__));
define("CORE_PATH", "app/core/");
define("APP_PATH", "app/");
define("ROOT_PATH", "public/");

// Incluir el autoloader
require CORE_PATH . 'Autoloader.php';

// Iniciar la aplicación
new App();

// use core\Router;

// $router = new Router();
// $router->dispatch();
