<?php

declare(strict_types=1);

namespace App\Core\View\Node;

use App\Core\View\TemplateEngine;

final class RangeNode implements NodeInterface
{
  /** @var NodeInterface[] */
  private array $children = [];

  public function __construct(
    private string $key
  ) {}

  public function addChild(NodeInterface $child): void
  {
    $this->children[] = $child;
  }

  public function render(array $data, mixed $currentItem = null): string
  {
    $value = TemplateEngine::lookup($data, $this->key, $currentItem);

    if (!is_array($value) && !($value instanceof \Traversable)) {
      return '';
    }

    $output = '';
    foreach ($value as $item) {
      foreach ($this->children as $child) {
        $output .= $child->render($data, $item);
      }
    }
    return $output;
  }
}