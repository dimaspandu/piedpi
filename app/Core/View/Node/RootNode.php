<?php

declare(strict_types=1);

namespace App\Core\View\Node;

use App\Core\View\TemplateEngine;

final class RootNode implements NodeInterface
{
  /** @var NodeInterface[] */
  private array $children = [];

  public function addChild(NodeInterface $child): void
  {
    $this->children[] = $child;
  }

  public function render(array $data, mixed $currentItem = null): string
  {
    $output = '';
    foreach ($this->children as $child) {
      $output .= $child->render($data, $currentItem);
    }
    return $output;
  }
}