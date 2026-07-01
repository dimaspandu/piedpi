<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\HealthController;
use App\Controllers\ItemController;
use App\Controllers\DbTestController;
use App\Controllers\ErrorController;

use App\Middleware\CsrfMiddleware;

/** @var Router $router */

$router->post('/api/test', [HomeController::class, 'api']);
$router->get('/health', [HealthController::class, 'check']);

/*
|----------------------------------------------------------
| Example REST-style endpoints
|----------------------------------------------------------
*/
$router->get('/api/items', [ItemController::class, 'index']);

/*
|-------------------------------------------------
| Example: Middleware usage on mutating routes
|-------------------------------------------------
| CsrfMiddleware is called before the controller for POST/PUT/DELETE.
| This demonstrates protecting state-changing endpoints.
*/
$router->post('/api/items', function () {
    CsrfMiddleware::handle();
    (new ItemController())->store();
});

$router->put('/api/items/:id', function (array $params) {
    CsrfMiddleware::handle();
    (new ItemController())->update($params);
});

$router->delete('/api/items/:id', function (array $params) {
    CsrfMiddleware::handle();
    (new ItemController())->destroy($params);
});

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
