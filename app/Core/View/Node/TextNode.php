<?php

declare(strict_types=1);

namespace App\Core\View\Node;

final class TextNode implements NodeInterface
{
  public function __construct(
    private string $content
  ) {}

  public function render(array $data, mixed $currentItem = null): string
  {
    return $this->content;
  }
}