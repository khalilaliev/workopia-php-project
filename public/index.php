<?php
require __DIR__ . '/../vendor/autoload.php';
require '../helpers.php';

use Framework\Router;

// spl_autoload_register(function ($class) {
//   $path = base_path('Framework/' . $class . '.php');
//   if (file_exists($path)) {
//     require $path;
//   }
// });

$router = new Router();
$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
