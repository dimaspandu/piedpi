<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\HealthController;
use App\Controllers\ItemController;
use App\Controllers\DbTestController;
use App\Controllers\ErrorController;

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

/*
|--------------------------------------------------------------------------
| Database Test Routes (Development Only)
|--------------------------------------------------------------------------
*/
if (
  env('APP_ENV') === 'development' &&
  env('APP_DEBUG') === true
) {
  $router->get('/_debug/db/items', [DbTestController::class, 'items']);
}

/*
|--------------------------------------------------------------------------
| Global Error Handlers
|--------------------------------------------------------------------------
| These handlers centralize API error responses
| and ensure consistent JSON output.
*/
$router->setNotFoundHandler([ErrorController::class, 'notFound']);
$router->setErrorHandler([ErrorController::class, 'serverError']);
