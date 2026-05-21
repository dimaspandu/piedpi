<?php

declare(strict_types=1);

namespace App\Core\Middleware;

/**
 * AuthMiddleware
 *
 * Example middleware for protecting routes that require authentication.
 * This is a demonstration only — replace with real auth logic as needed.
 */
final class AuthMiddleware
{
  /**
   * Checks for a valid authorization token.
   * In a real app this would validate session, JWT, etc.
   */
  public static function handle(): void
  {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

    // Demo: accept only "Bearer demo-secret-token"
    if ($authHeader !== 'Bearer demo-secret-token') {
      http_response_code(401);
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Unauthorized']);
      exit;
    }
  }
}
