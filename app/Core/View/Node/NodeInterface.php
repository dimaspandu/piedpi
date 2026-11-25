<?php

declare(strict_types=1);

namespace App\Core\View\Node;

interface NodeInterface
{
  public function render(array $data, mixed $currentItem = null): string;
}