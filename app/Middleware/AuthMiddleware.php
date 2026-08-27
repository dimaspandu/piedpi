<?php

declare(strict_types=1);

namespace App\Middleware;

final class AuthMiddleware
{
  public static function handle(): void
  {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

    if ($authHeader !== 'Bearer demo-secret-token') {
      http_response_code(401);
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Unauthorized']);
      exit;
    }
  }
}