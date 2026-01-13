<?php

use Core\Router;
use Core\Session;
use Core\ValidationException;

const BASE_PATH = __DIR__.'/../';
const VIEWS_PATH = __DIR__ . '/../src/views/';

session_start();

require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'Core/functions.php';

$router = new Router();
require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    $router->route($uri, $method);
} catch (ValidationException $exception) {
    Session::set_flash('errors', $exception->errors);
    Session::set_flash('old', $exception->old);

    redirect_to($router->previousUrl());
}

Session::clear_flash();
