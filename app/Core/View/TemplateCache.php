<?php

declare(strict_types=1);

namespace App\Core\View;

final class TemplateCache
{
  private static string $dir = __DIR__ . '/../../../storage/cache';

  public static function get(string $key): ?string
  {
    $file = self::$dir . '/' . md5($key);
    return is_file($file) ? file_get_contents($file) : null;
  }

  public static function put(string $key, string $content): void
  {
    if (!is_dir(self::$dir)) {
      mkdir(self::$dir, 0777, true);
    }

    file_put_contents(self::$dir . '/' . md5($key), $content);
  }
}
