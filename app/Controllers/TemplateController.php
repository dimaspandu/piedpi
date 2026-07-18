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
        'title' => 'Piedpi Template Demo',
        'version' => '1.0.0',
        'html' => '<strong>unescaped</strong>',
      ]
    );

    Renderer::end();
  }

  public function items(): void
  {
    Renderer::start();

    $items = [
      ['name' => 'Alpha', 'price' => 10000],
      ['name' => 'Beta', 'price' => 25000],
      ['name' => 'Gamma', 'price' => 50000],
    ];

    Renderer::chunk('<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Items</title></head><body>');
    Renderer::chunk('<h1>Product List</h1>');
    Renderer::chunk('<ul>');

    foreach ($items as $item) {
      Renderer::template(
        dirname(__DIR__) . '/Views/template_item.php',
        [
          'name' => $item['name'],
          'price' => $item['price'],
        ]
      );
    }

    Renderer::chunk('</ul>');
    Renderer::chunk('</body></html>');

    Renderer::end();
  }
}
