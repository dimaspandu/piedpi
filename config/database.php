<?php

declare(strict_types=1);

/**
 * Database configuration.
 *
 * Used by the PDO connection factory.
 */

return [
  'driver'   => 'mysql',
  'host'     => getenv('DB_HOST') ?: '127.0.0.1',
  'port'     => getenv('DB_PORT') ?: '3306',
  'database' => getenv('DB_NAME') ?: '',
  'username' => getenv('DB_USER') ?: '',
  'password' => getenv('DB_PASS') ?: '',
  'charset'  => 'utf8mb4',
  'collation'=> 'utf8mb4_unicode_ci',
  'options'  => [
    // PDO options will be applied later in the PDO wrapper
  ],
];
