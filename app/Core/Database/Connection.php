<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;
use PDOException;

/**
 * Class Connection
 *
 * Manages a single PDO connection instance.
 */
final class Connection
{
  private static ?PDO $pdo = null;

  public static function get(): PDO
  {
    if (self::$pdo !== null) {
      return self::$pdo;
    }

    $config = require dirname(__DIR__, 3) . '/config/database.php';

    $dsn = sprintf(
      '%s:host=%s;dbname=%s;charset=%s',
      $config['driver'],
      $config['host'],
      $config['database'],
      $config['charset']
    );

    try {
      self::$pdo = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES   => false,
        ]
      );
    } catch (PDOException $e) {
      throw new DatabaseException(
        'Failed to connect to database',
        $e
      );
    }

    return self::$pdo;
  }
}
