<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class Widget
 *
 * Simple HTML component renderer.
 */
class Widget
{
  /**
   * Render an HTML element.
   *
   * @param string $tag
   * @param array<string, mixed>|null $attributes
   * @param array<string>|string|null $children
   */
  public static function render(
    string $tag,
    ?array $attributes = null,
    array|string|null $children = null
  ): string {
    $attrString = self::buildAttributes($attributes);
    $content = self::buildChildren($children);

    return "<{$tag}{$attrString}>{$content}</{$tag}>";
  }

  /**
   * Build HTML attributes string.
   *
   * @param array<string, mixed>|null $attributes
   */
  private static function buildAttributes(?array $attributes): string
  {
    if (empty($attributes)) {
      return '';
    }

    $parts = [];

    foreach ($attributes as $key => $value) {
      $escapedKey = htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8');

      if ($value === true) {
        $parts[] = $escapedKey;
        continue;
      }

      if ($value === false || $value === null) {
        continue;
      }

      $escapedValue = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
      $parts[] = "{$escapedKey}=\"{$escapedValue}\"";
    }

    return ' ' . implode(' ', $parts);
  }

  /**
   * Build HTML children content.
   *
   * @param array<string>|string|null $children
   */
  private static function buildChildren(array|string|null $children): string
  {
    if ($children === null) {
      return '';
    }

    if (is_string($children)) {
      return htmlspecialchars($children, ENT_QUOTES, 'UTF-8');
    }

    $output = '';

    foreach ($children as $child) {
      $output .= is_string($child)
        ? htmlspecialchars($child, ENT_QUOTES, 'UTF-8')
        : $child;
    }

    return $output;
  }
}
