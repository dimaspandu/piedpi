<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database\Database;
use App\Core\Http\JsonResponse;

/**
 * DbTestController
 *
 * Used to verify database connectivity and basic queries.
 * This controller intentionally uses the Database wrapper
 * instead of raw PDO.
 */
final class DbTestController
{
  /**
   * GET /_debug/db/items
   *
   * Fetch items from database using the safe PDO wrapper.
   */
  public function items(): JsonResponse
  {
    $db = new Database();

    $items = $db->select(
      'SELECT id, name, created_at FROM items'
    );

    return new JsonResponse([
      'status' => 'ok',
      'items'  => $items,
    ]);
  }
}
