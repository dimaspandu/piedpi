<?php

declare(strict_types=1);

namespace App\Core\View;

use App\Core\View\Node\NodeInterface;
use App\Core\View\Node\RootNode;
use App\Core\View\Node\TextNode;
use App\Core\View\Node\VarNode;
use App\Core\View\Node\IfNode;
use App\Core\View\Node\RangeNode;

final class TemplateEngine
{
  public static function render(string $template, array $data = []): string
  {
    $tokens = self::tokenize($template);
    $ast = self::parse($tokens);
    return self::renderNode($ast, $data);
  }

  public static function tokenize(string $template): array
  {
    $tokens = [];
    $pos = 0;
    $len = strlen($template);

    while ($pos < $len) {
      $nextAction = strpos($template, '{{', $pos);
      $nextVar = strpos($template, '}}', $pos);

      if ($nextAction === false) {
        $tokens[] = ['type' => 'text', 'value' => substr($template, $pos)];
        break;
      }

      if ($nextAction > $pos) {
        $tokens[] = ['type' => 'text', 'value' => substr($template, $pos, $nextAction - $pos)];
      }

      if ($nextVar === false || $nextVar < $nextAction) {
        $tokens[] = ['type' => 'text', 'value' => '{{'];
        $pos = $nextAction + 2;
        continue;
      }

      $content = trim(substr($template, $nextAction + 2, $nextVar - $nextAction - 2));
      $tokens[] = self::parseAction($content);
      $pos = $nextVar + 2;
    }

    return $tokens;
  }

  private static function parseAction(string $content): array
  {
    $parts = preg_split('/\s+/', $content, 2);
    $keyword = strtolower($parts[0] ?? '');

    switch ($keyword) {
      case 'if':
        return ['type' => 'if', 'condition' => $parts[1] ?? ''];
      case 'else':
        return ['type' => 'else'];
      case 'end':
        return ['type' => 'end'];
      case 'range':
        return ['type' => 'range', 'key' => $parts[1] ?? ''];
      default:
        return ['type' => 'var', 'key' => $content];
    }
  }

  public static function parse(array $tokens): RootNode
  {
    $root = new RootNode();
    $stack = [$root];
    $current = $root;

    foreach ($tokens as $token) {
      switch ($token['type']) {
        case 'text':
          $current->addChild(new TextNode($token['value']));
          break;

        case 'var':
          $current->addChild(new VarNode($token['key']));
          break;

        case 'if':
          $ifNode = new IfNode($token['condition']);
          $current->addChild($ifNode);
          $stack[] = $ifNode;
          $current = $ifNode;
          break;

        case 'else':
          if (count($stack) > 0) {
            $parent = $stack[count($stack) - 1];
            if ($parent instanceof IfNode) {
              $parent->setElseBranch([]);
              $current = $parent;
            }
          }
          break;

        case 'range':
          $rangeNode = new RangeNode($token['key']);
          $current->addChild($rangeNode);
          $stack[] = $rangeNode;
          $current = $rangeNode;
          break;

        case 'end':
          if (count($stack) > 1) {
            array_pop($stack);
            $current = $stack[count($stack) - 1];
          }
          break;
      }
    }

    return $root;
  }

  public static function renderNode(NodeInterface $node, array $data, mixed $currentItem = null): string
  {
    return $node->render($data, $currentItem);
  }

  public static function lookup(array $data, string $key, mixed $currentItem = null): mixed
  {
    if ($key === '.') {
      return $currentItem ?? $data;
    }

    if ($key === '') {
      return '';
    }

    if (str_starts_with($key, '.')) {
      $field = substr($key, 1);
      $source = $currentItem ?? $data;
      if (is_array($source) && array_key_exists($field, $source)) {
        return $source[$field];
      }
      if (is_object($source) && property_exists($source, $field)) {
        return $source->$field;
      }
      return '';
    }

    if (array_key_exists($key, $data)) {
      return $data[$key];
    }

    if (is_array($data) && array_key_exists($key, $data)) {
      return $data[$key];
    }

    if ($currentItem !== null) {
      if (is_array($currentItem) && array_key_exists($key, $currentItem)) {
        return $currentItem[$key];
      }
      if (is_object($currentItem) && property_exists($currentItem, $key)) {
        return $currentItem->$key;
      }
    }

    return '';
  }
}