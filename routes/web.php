<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\DistController;

/** @var Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/hello-cached', [HomeController::class, 'cached']);
$router->get('/about', [AboutController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Serve bundled frontend (gzip-enabled)
|--------------------------------------------------------------------------
| Example: /dist/app
*/
$router->get('/dist/:name', [DistController::class, 'serve']);
