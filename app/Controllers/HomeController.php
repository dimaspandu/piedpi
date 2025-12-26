<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Http\JsonResponse;
use App\Core\Renderer;
use App\Core\View\TemplateCache;

/**
 * Class HomeController
 *
 * Handles the home page.
 */
class HomeController
{
  public function index(): void
  {
    Renderer::start();

    Renderer::chunk('<!DOCTYPE html>');
    Renderer::chunk('<html lang="en">');
    Renderer::chunk('<body>');

    Renderer::chunk(
      widget('h1', null, 'Streaming Content')
    );

    // Simulate network / rendering delay
    usleep(500_000);

    Renderer::chunk('<p>Step 2</p>');
    
    // Simulate network / rendering delay
    usleep(500_000);

    Renderer::chunk('<p>Step 3</p>');
    
    // Simulate network / rendering delay
    usleep(500_000);

    Renderer::view(
      dirname(__DIR__) . '/Views/partial.php'
    );

    Renderer::chunk('</body>');
    Renderer::chunk('</html>');

    Renderer::end();
  }

  public function cached(): void
  {
    $key = 'home_page';

    if ($cached = TemplateCache::get($key)) {
      echo $cached;
      return;
    }

    ob_start();

    Renderer::start();
    Renderer::chunk('<h1>Hello Cached</h1>');
    Renderer::end();

    $html = ob_get_clean();

    TemplateCache::put($key, $html);
    echo $html;
  }

  public function api(): JsonResponse
  {
    return new JsonResponse([
      'status' => 'ok',
      'time' => time(),
    ]);
  }
}
