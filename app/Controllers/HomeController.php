<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Renderer;

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

    Renderer::view(
      dirname(__DIR__) . '/Views/partial.php'
    );

    Renderer::chunk('</body>');
    Renderer::chunk('</html>');

    Renderer::end();
  }
}
