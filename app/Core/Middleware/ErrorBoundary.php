<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\ErrorHandler;
use Throwable;

/**
 * Error boundary middleware.
 *
 * Acts as a safety wrapper around request execution,
 * preventing unhandled exceptions from crashing the process.
 */
final class ErrorBoundary
{
  /**
   * Execute the given callable inside a try/catch boundary.
   *
   * Any thrown exception is delegated to the central ErrorHandler.
   */
  public function handle(callable $next): void
  {
    try {
      $next();
    } catch (Throwable $e) {
      ErrorHandler::handle($e);
    }
  }
}
