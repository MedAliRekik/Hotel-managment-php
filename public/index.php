<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/helpers/Csrf.php';
Csrf::token();

$controllerName = strtolower($_GET['controller'] ?? 'auth');
$action = $_GET['action'] ?? 'login';

$routes = [
    'auth' => ['file' => __DIR__ . '/../app/controllers/AuthController.php', 'class' => 'AuthController'],
    'clientauth' => ['file' => __DIR__ . '/../app/controllers/ClientAuthController.php', 'class' => 'ClientAuthController'],
    'client' => ['file' => __DIR__ . '/../app/controllers/ClientController.php', 'class' => 'ClientController'],
    'room' => ['file' => __DIR__ . '/../app/controllers/RoomController.php', 'class' => 'RoomController'],
    'reservation' => ['file' => __DIR__ . '/../app/controllers/ReservationController.php', 'class' => 'ReservationController'],
    'clientportal' => ['file' => __DIR__ . '/../app/controllers/ClientPortalController.php', 'class' => 'ClientPortalController'],
];

if ($controllerName === 'dashboard') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    require __DIR__ . '/../app/views/dashboard/index.php';
    exit;
}

if (!isset($routes[$controllerName])) {
    http_response_code(404);
    echo 'Controller not found.';
    exit;
}

$route = $routes[$controllerName];
require_once $route['file'];
$controller = new $route['class']();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Action not found.';
    exit;
}

$controller->{$action}();
