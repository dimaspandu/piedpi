<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$files = glob(__DIR__ . '/*Test.php');

foreach ($files as $file) {
  require $file;
}

echo "All tests passed\n";
