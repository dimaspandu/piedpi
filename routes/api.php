<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\HealthController;
use App\Controllers\ItemController;

/** @var Router $router */

$router->post('/api/test', [HomeController::class, 'api']);
$router->get('/health', [HealthController::class, 'check']);

/*
|----------------------------------------------------------
| Example REST-style endpoints
|----------------------------------------------------------
*/
$router->get('/api/items', [ItemController::class, 'index']);
$router->post('/api/items', [ItemController::class, 'store']);
$router->put('/api/items/:id', [ItemController::class, 'update']);
$router->delete('/api/items/:id', [ItemController::class, 'destroy']);
