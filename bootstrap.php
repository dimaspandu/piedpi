<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Autoloader (PSR-4 like, no Composer)
|--------------------------------------------------------------------------
*/
spl_autoload_register(function (string $class): void {
  $prefix = 'App\\';
  $baseDir = __DIR__ . '/app/';

  if (!str_starts_with($class, $prefix)) {
    return;
  }

  $relativeClass = substr($class, strlen($prefix));
  $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

  if (is_file($file)) {
    require $file;
  }
});

/*
|--------------------------------------------------------------------------
| Load Application Configuration
|--------------------------------------------------------------------------
*/
$appConfig = require __DIR__ . '/config/app.php';

/*
|--------------------------------------------------------------------------
| PHP Error Configuration
|--------------------------------------------------------------------------
| Errors are never displayed to users.
| All errors must be logged internally.
*/
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

if (
  ($appConfig['env'] ?? 'production') === 'development' &&
  ($appConfig['debug'] ?? false) === true
) {
  ini_set('display_errors', '1');
}

/*
|--------------------------------------------------------------------------
| Global Helper Functions
|--------------------------------------------------------------------------
*/

use App\Core\Widget;

/**
 * Render an HTML widget component.
 *
 * @param string $tag
 * @param array<string, mixed>|null $attrs
 * @param array|string|null $children
 */
function widget(
  string $tag,
  ?array $attrs = null,
  array|string|null $children = null
): string {
  return Widget::render($tag, $attrs, $children);
}
