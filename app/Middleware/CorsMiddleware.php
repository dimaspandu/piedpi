<?php

declare(strict_types=1);

namespace App\Middleware;

final class CorsMiddleware
{
    public static function handle(): void
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;

        if ($origin === null) {
            return;
        }

        $raw = env('CORS_ALLOWED_ORIGINS', '');

        if ($raw === '') {
            return;
        }

        $allowedOrigins = array_map(
            static fn(string $o): string => rtrim(trim($o), '/'),
            explode(',', $raw)
        );

        $normalizedOrigin = rtrim($origin, '/');

        if (!in_array($normalizedOrigin, $allowedOrigins, true)) {
            return;
        }

        header("Access-Control-Allow-Origin: {$origin}");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');
        header('Vary: Origin');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}