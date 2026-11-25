<?php

declare(strict_types=1);

namespace App\Core\View\Node;

use App\Core\View\TemplateEngine;

final class VarNode implements NodeInterface
{
  public function __construct(
    private string $key
  ) {}

  public function render(array $data, mixed $currentItem = null): string
  {
    $value = TemplateEngine::lookup($data, $this->key, $currentItem);
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}