<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database\Connection;
use App\Core\Http\JsonResponse;

/**
 * DbTestController
 *
 * Used to verify database connectivity and queries.
 */
final class DbTestController
{
  // public function items(): JsonResponse
  public function items()
  {
    $pdo = Connection::get();

    $stmt = $pdo->query('SELECT id, name, created_at FROM items');

    return new JsonResponse([
      'status' => 'ok',
      'items'  => $stmt->fetchAll(),
    ]);
  }
}
