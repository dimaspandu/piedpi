<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Global Exception Boundary (EARLY)
|--------------------------------------------------------------------------
| Catches all uncaught exceptions including bootstrap failures.
*/
set_exception_handler(function (Throwable $e): void {
  // Delegate to ErrorHandler after autoload is available
  if (class_exists(\App\Core\ErrorHandler::class)) {
    \App\Core\ErrorHandler::handle($e);
    return;
  }

  // Fallback (very early failure)
  http_response_code(500);
  error_log((string) $e);

  echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Server Error</title>
</head>
<body>
  <h1>500 - Server Error</h1>
  <p>Something went wrong.</p>
</body>
</html>
HTML;
  exit;
});

/*
|--------------------------------------------------------------------------
| Bootstrap Application
|--------------------------------------------------------------------------
*/
require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Router;
use App\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Register Routes
|--------------------------------------------------------------------------
*/
$router = new Router();
$router->get('/', [HomeController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Dispatch Request
|--------------------------------------------------------------------------
*/
$router->dispatch();
