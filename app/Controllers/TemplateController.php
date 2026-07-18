<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Renderer;

final class TemplateController
{
  public function index(): void
  {
    Renderer::start();

    Renderer::template(
      dirname(__DIR__) . '/Views/template_demo.php',
      [
        'title' => 'Piedpi Frontend Services',
        'version' => '1.1.0',
        'html' => '<strong>unescaped</strong>',
      ]
    );

    Renderer::end();
  }

  public function items(): void
  {
    Renderer::start();

    $items = [
      ['name' => 'App A', 'size' => '1.2MB'],
      ['name' => 'App B', 'size' => '3.4MB'],
      ['name' => 'App C', 'size' => '0.8MB'],
    ];

    Renderer::chunk('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Builds</title></head><body>');
    Renderer::chunk('<h1>Frontend Builds</h1>');
    Renderer::chunk('<ul>');

    foreach ($items as $item) {
      Renderer::template(
        dirname(__DIR__) . '/Views/template_item.php',
        [
          'name' => $item['name'],
          'size' => $item['size'],
        ]
      );
    }

    Renderer::chunk('</ul>');
    Renderer::chunk('</body></html>');

    Renderer::end();
  }
}
