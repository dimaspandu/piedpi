<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HealthController;
use App\Controllers\ItemController;
use App\Controllers\DbTestController;
use App\Controllers\ErrorController;

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
| Used by load balancers, uptime monitors, or orchestration tools.
*/
$router->get('/', [HealthController::class, 'check']);

/*
|--------------------------------------------------------------------------
| Debug Routes (Development Only)
|--------------------------------------------------------------------------
| Intentionally throws an exception to test global error handling.
*/
if (
  env('APP_ENV') === 'development' &&
  env('APP_DEBUG') === true
) {
  $router->get('/_debug/500', function () {
    throw new Exception('Forced 500 error');
  });
}

/*
|--------------------------------------------------------------------------
| REST-style API Endpoints
|--------------------------------------------------------------------------
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
