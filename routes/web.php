<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\DistController;
use App\Controllers\ErrorController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/hello', [HomeController::class, 'hello']);

/*
|--------------------------------------------------------------------------
| Serve bundled frontend (gzip-enabled)
|--------------------------------------------------------------------------
| Example: /dist/app
*/
$router->get('/dist/:name', [DistController::class, 'serve']);

/*
|-------------------------------------------------
| Global error handlers
|-------------------------------------------------
| These handlers replace hardcoded error output.
| They allow full HTML pages for 404 and 500.
*/
$router->setNotFoundHandler([ErrorController::class, 'notFound']);
$router->setErrorHandler([ErrorController::class, 'serverError']);
