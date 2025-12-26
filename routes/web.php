<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/hello-cached', [HomeController::class, 'cached']);
$router->get('/about', [AboutController::class, 'index']);
