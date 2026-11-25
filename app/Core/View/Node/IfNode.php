<?php

declare(strict_types=1);

namespace App\Core\View\Node;

use App\Core\View\TemplateEngine;

final class IfNode implements NodeInterface
{
  /** @var NodeInterface[] */
  private array $thenBranch = [];
  /** @var NodeInterface[] */
  private array $elseBranch = [];
  private bool $hasElse = false;

  public function __construct(
    private string $condition
  ) {}

  public function addChild(NodeInterface $child): void
  {
    if ($this->hasElse) {
      $this->elseBranch[] = $child;
    } else {
      $this->thenBranch[] = $child;
    }
  }

  public function setElseBranch(array $branch = []): void
  {
    $this->hasElse = true;
    $this->elseBranch = $branch;
  }

  public function render(array $data, mixed $currentItem = null): string
  {
    $value = TemplateEngine::lookup($data, $this->condition, $currentItem);
    $truthy = self::isTruthy($value);

    $branch = $truthy ? $this->thenBranch : $this->elseBranch;

    $output = '';
    foreach ($branch as $child) {
      $output .= $child->render($data, $currentItem);
    }
    return $output;
  }

  private static function isTruthy(mixed $value): bool
  {
    if (is_null($value)) {
      return false;
    }
    if (is_bool($value)) {
      return $value;
    }
    if (is_int($value) || is_float($value)) {
      return $value !== 0;
    }
    if (is_string($value)) {
      return $value !== '' && strtolower($value) !== 'false';
    }
    if (is_array($value)) {
      return count($value) > 0;
    }
    if (is_object($value)) {
      return true;
    }
    return false;
  }
}