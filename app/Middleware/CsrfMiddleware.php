<?php

declare(strict_types=1);

namespace App\Middleware;

final class CsrfMiddleware
{
    public static function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return;
        }

        $token = $_POST['csrf_token'] ?? '';

        if ($token !== 'demo-csrf-token-123') {
            http_response_code(419);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'CSRF token mismatch']);
            exit;
        }
    }
}