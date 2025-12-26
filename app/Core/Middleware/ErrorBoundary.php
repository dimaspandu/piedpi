<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\ErrorHandler;
use Throwable;

final class ErrorBoundary
{
  public function handle(callable $next): void
  {
    try {
      $next();
    } catch (Throwable $e) {
      ErrorHandler::handle($e);
    }
  }
}
