<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;
use PDOStatement;

/**
 * Database
 *
 * Thin, safe wrapper around PDO.
 * Ensures:
 * - Prepared statements only
 * - No SQL string interpolation
 * - Explicit and predictable API
 */
final class Database
{
  private PDO $pdo;

  public function __construct(?PDO $pdo = null)
  {
    $this->pdo = $pdo ?? Connection::get();
  }

  /**
   * Run a SELECT query and return all rows.
   *
   * @param string $sql
   * @param array<string, mixed> $params
   */
  public function select(string $sql, array $params = []): array
  {
    $stmt = $this->prepareAndExecute($sql, $params);
    return $stmt->fetchAll();
  }

  /**
   * Run a SELECT query and return the first row.
   */
  public function first(string $sql, array $params = []): ?array
  {
    $stmt = $this->prepareAndExecute($sql, $params);
    $row = $stmt->fetch();

    return $row === false ? null : $row;
  }

  /**
   * Execute INSERT / UPDATE / DELETE.
   *
   * Returns affected row count.
   */
  public function execute(string $sql, array $params = []): int
  {
    $stmt = $this->prepareAndExecute($sql, $params);
    return $stmt->rowCount();
  }

  /**
   * Execute an INSERT statement and return last insert ID.
   */
  public function insert(string $sql, array $params = []): int
  {
    $this->prepareAndExecute($sql, $params);
    return (int) $this->pdo->lastInsertId();
  }

  /**
   * Run multiple operations inside a transaction.
   *
   * @template T
   * @param callable(self):T $callback
   * @return T
   */
  public function transaction(callable $callback): mixed
  {
    try {
      $this->pdo->beginTransaction();

      $result = $callback($this);

      $this->pdo->commit();
      return $result;
    } catch (\Throwable $e) {
      if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
      }

      throw $e;
    }
  }

  /**
   * Prepare and execute a statement safely.
   *
   * @param array<string, mixed> $params
   */
  private function prepareAndExecute(
    string $sql,
    array $params
  ): PDOStatement {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt;
  }
}
