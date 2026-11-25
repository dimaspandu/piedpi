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
    return htmlspecialchars(self::valueToString($value), ENT_QUOTES, 'UTF-8');
  }

  private static function valueToString(mixed $value): string
  {
    if (is_array($value) || is_object($value)) {
      return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    return (string) $value;
  }
}