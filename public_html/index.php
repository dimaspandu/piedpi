<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Global Exception Boundary (EARLY)
|--------------------------------------------------------------------------
*/
set_exception_handler(function (Throwable $e): void {
  if (class_exists(\App\Core\ErrorHandler::class)) {
    \App\Core\ErrorHandler::handle($e);
    return;
  }

  http_response_code(500);
  error_log((string) $e);
  echo '<h1>500 - Server Error</h1>';
  exit;
});

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/
require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Router;

/*
|--------------------------------------------------------------------------
| Register Routes
|--------------------------------------------------------------------------
*/
$router = new Router();

require dirname(__DIR__) . '/routes/web.php';
require dirname(__DIR__) . '/routes/api.php';

/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
*/
$router->dispatch();
