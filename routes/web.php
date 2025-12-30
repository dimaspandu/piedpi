<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\DistController;
use App\Controllers\ErrorController;

/** @var Router $router */

/*
|-------------------------------------------------
| Primary application routes
|-------------------------------------------------
| These routes render streamed or full HTML views.
*/
$router->get('/', [HomeController::class, 'index']);
$router->get('/hello', [HomeController::class, 'hello']);
$router->get('/about', [AboutController::class, 'index']);
$router->get('/_debug/500', function () {
  throw new Exception('Forced 500 error');
});

/*
|-------------------------------------------------
| Global error handlers
|-------------------------------------------------
| These handlers replace hardcoded error output.
| They allow full HTML pages for 404 and 500.
*/
$router->setNotFoundHandler([ErrorController::class, 'notFound']);
$router->setErrorHandler([ErrorController::class, 'serverError']);

/*
|-------------------------------------------------
| Serve bundled frontend assets (gzip-enabled)
|-------------------------------------------------
| Intended for serving pre-built frontend output
| such as Vite / Webpack / Rollup bundles.
|
| Example:
|   /dist/app   -> dist/app.html (gzipped)
|
| Keeps frontend delivery explicit and controlled,
| without relying on web server rewrite rules.
*/
$router->get('/dist/:name', [DistController::class, 'serve']);
