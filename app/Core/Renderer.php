<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class Renderer
 *
 * Responsible for rendering and streaming HTML output.
 * Designed for low-memory usage and progressive rendering.
 */
class Renderer
{
  /**
   * Start output buffering if not already started.
   */
  public static function start(): void
  {
    if (ob_get_level() === 0) {
      ob_start();
    }
  }

  /**
   * Output a raw HTML chunk.
   *
   * @param string $html
   */
  public static function chunk(string $html): void
  {
    echo $html;
    self::flush();
  }

  /**
   * Render a PHP view file.
   *
   * @param string $path
   * @param array<string, mixed> $data
   */
  public static function view(string $path, array $data = []): void
  {
    if (!is_file($path)) {
      throw new \RuntimeException("View file not found: {$path}");
    }

    extract($data, EXTR_SKIP);
    require $path;
  }

  /**
   * Flush output buffers to the client.
   */
  public static function flush(): void
  {
    if (ob_get_level() > 0) {
      ob_flush();
    }

    flush();
  }

  /**
   * End output buffering.
   */
  public static function end(): void
  {
    if (ob_get_level() > 0) {
      ob_end_flush();
    }
  }
}
