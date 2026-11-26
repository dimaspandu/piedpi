<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\View\TemplateCache;
use App\Core\View\TemplateEngine;

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
   * This method defines a streaming boundary:
   * - The view may output multiple chunks internally
   * - Output buffer is flushed after the view completes
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

    // flush after view execution
    self::flush();
  }

  /**
   * Render a compiled template view with expression interpolation.
   *
   * Supports Blade-like syntax:
   *   {{ $var }}  -> escaped output
   *   {{ var }}   -> escaped output (auto-prefix $)
   *   {!! $html }> -> unescaped output
   *
   * @param string $path
   * @param array<string, mixed> $data
   */
  public static function template(string $path, array $data = []): void
  {
    if (!is_file($path)) {
      throw new \RuntimeException("Template file not found: {$path}");
    }

    $compiledPath = self::compileTemplate($path);

    extract($data, EXTR_SKIP);
    require $compiledPath;

    self::flush();
  }

  /**
   * Render a Go-like template with control structures.
   *
   * Supports Go template syntax:
   *   {{ key }}       -> escaped variable (backward compatible)
   *   {{ .key }}      -> Go-style field access (data['key'])
   *   {{ . }}         -> render entire current context
   *   {{ if key }}...{{ end }}       -> conditional
   *   {{ if key }}...{{ else }}...{{ end }} -> conditional with else
   *   {{ range key }}...{{ end }}    -> iterate array
   *
   * @param string $path
   * @param array<string, mixed> $data
   */
  public static function goTemplate(string $path, array $data = []): void
  {
    if (!is_file($path)) {
      throw new \RuntimeException("Template file not found: {$path}");
    }

    $key = 'gotemplate_' . md5((string) filemtime($path) . file_get_contents($path));

    $ast = null;
    if ($cached = TemplateCache::get($key)) {
      $ast = @unserialize($cached);
    }

    if (!$ast instanceof \App\Core\View\Node\RootNode) {
      $content = file_get_contents($path);
      $tokens = \App\Core\View\TemplateEngine::tokenize($content);
      $ast = \App\Core\View\TemplateEngine::parse($tokens);
      TemplateCache::put($key, serialize($ast));
    }

    echo \App\Core\View\TemplateEngine::renderNode($ast, $data);
    self::flush();
  }

  /**
   * Compile a template file into plain PHP and return the compiled path.
   *
   * @return string
   */
  private static function compileTemplate(string $path): string
  {
    $key = 'template_' . md5((string) filemtime($path) . file_get_contents($path));

    if ($compiled = TemplateCache::get($key)) {
      if (is_file($compiled)) {
        return $compiled;
      }
    }

    $content = file_get_contents($path);

    $compiled = preg_replace_callback(
      '/{{(.+?)}}/',
      function ($matches) {
        $expr = trim($matches[1]);
        if (!str_starts_with($expr, '$') && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $expr)) {
          $expr = '$' . $expr;
        }
        return '<?= htmlspecialchars(' . $expr . ') ?>';
      },
      $content
    );

    $compiled = preg_replace(
      '/{!!(.+?)!!}/',
      '<?= $1 ?>',
      $compiled
    );

    $compiledDir = __DIR__ . '/../../storage/compiled';
    $compiledPath = $compiledDir . '/piedpi_template_' . md5($path) . '.php';

    if (!is_dir($compiledDir)) {
      mkdir($compiledDir, 0777, true);
    }

    file_put_contents($compiledPath, $compiled);
    TemplateCache::put($key, $compiledPath);

    return $compiledPath;
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

  /**
   * Serve a full file (HTML, JS, CSS) with optional gzip.
   *
   * @param string $path
   * @param array{
   *   gzip?: bool,
   *   contentType?: string
   * } $options
   */
  public static function serve(string $path, array $options = []): void
  {
    if (!is_file($path)) {
      ErrorHandler::renderNotFound();
      return;
    }

    $gzip = $options['gzip'] ?? false;
    $contentType = $options['contentType'] ?? 'text/html';

    header('Content-Type: ' . $contentType);
    header('Vary: Accept-Encoding');

    $content = file_get_contents($path);

    if ($gzip && self::clientAcceptsGzip()) {
      $compressed = gzencode($content, 9);

      header('Content-Encoding: gzip');
      header('Content-Length: ' . strlen($compressed));

      echo $compressed;
      return;
    }

    header('Content-Length: ' . strlen($content));
    echo $content;
  }

  /**
   * Check if client supports gzip.
   */
  private static function clientAcceptsGzip(): bool
  {
    if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
      return false;
    }

    return str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
  }
}
