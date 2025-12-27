<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\ErrorHandler;
use App\Core\Http\Response;
use App\Core\Middleware\ErrorBoundary;

/**
 * Lightweight HTTP Router using a Trie-based structure.
 *
 * Responsibilities:
 * - HTTP method matching
 * - Path resolution
 * - Dynamic parameter extraction
 * - Error boundary execution
 *
 * Intentionally framework-agnostic:
 * - No container
 * - No auto-wiring
 * - Explicit control flow
 */
class Router
{
  /**
   * Root node of the route trie.
   */
  private TrieNode $root;

  public function __construct()
  {
    $this->root = new TrieNode();
  }

  /**
   * Register a GET route.
   */
  public function get(string $path, callable|array $handler): void
  {
    $this->addRoute('GET', $path, $handler);
  }

  /**
   * Register a POST route.
   */
  public function post(string $path, callable|array $handler): void
  {
    $this->addRoute('POST', $path, $handler);
  }

  /**
   * Register a PUT route.
   */
  public function put(string $path, callable|array $handler): void
  {
    $this->addRoute('PUT', $path, $handler);
  }

  /**
   * Register a PATCH route.
   */
  public function patch(string $path, callable|array $handler): void
  {
    $this->addRoute('PATCH', $path, $handler);
  }

  /**
   * Register a DELETE route.
   */
  public function delete(string $path, callable|array $handler): void
  {
    $this->addRoute('DELETE', $path, $handler);
  }

  /**
   * Add a route definition into the trie.
   *
   * Dynamic parameters are stored internally using "*"
   * Example: /users/:id
   */
  private function addRoute(
    string $httpMethod,
    string $path,
    callable|array $handler
  ): void {
    $segments = explode('/', trim($path, '/'));
    $currentNode = $this->root;

    foreach ($segments as $segment) {
      $key = $segment;

      // Dynamic parameter segment
      if (str_starts_with($segment, ':')) {
        $key = '*';
        $currentNode->children[$key] ??= new TrieNode();
        $currentNode->children[$key]->paramName = substr($segment, 1);
      } else {
        $currentNode->children[$key] ??= new TrieNode();
      }

      $currentNode = $currentNode->children[$key];
    }

    $currentNode->handlers[$httpMethod] = $handler;
  }

  /**
   * Dispatch the current HTTP request.
   *
   * Handles:
   * - Base path normalization
   * - Route matching
   * - Parameter binding
   * - Error boundary execution
   */
  public function dispatch(): void
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $requestPath   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

    /*
    |--------------------------------------------------------------------------
    | Normalize Base Path
    |--------------------------------------------------------------------------
    */
    $basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';

    if ($basePath !== '' && str_starts_with($requestPath, $basePath)) {
      $requestPath = substr($requestPath, strlen($basePath));
    }

    $requestPath = '/' . trim($requestPath, '/');

    /*
    |--------------------------------------------------------------------------
    | Match Route
    |--------------------------------------------------------------------------
    */
    $segments = explode('/', trim($requestPath, '/'));
    $params   = [];

    $currentNode = $this->root;

    foreach ($segments as $segment) {
      if (isset($currentNode->children[$segment])) {
        $currentNode = $currentNode->children[$segment];
        continue;
      }

      if (isset($currentNode->children['*'])) {
        $currentNode = $currentNode->children['*'];
        $params[$currentNode->paramName] = $segment;
        continue;
      }

      ErrorHandler::renderNotFound();
      return;
    }

    if (!isset($currentNode->handlers[$requestMethod])) {
      ErrorHandler::renderNotFound();
      return;
    }

    $handler  = $currentNode->handlers[$requestMethod];
    $boundary = new ErrorBoundary();

    $boundary->handle(function () use ($handler, $params) {
      $this->executeHandler($handler, $params);
    });
  }

  /**
   * Execute the resolved route handler.
   *
   * If the handler returns a Response instance,
   * it will be sent automatically.
   */
  private function executeHandler(callable|array $handler, array $params): void
  {
    if (is_array($handler)) {
      [$class, $method] = $handler;
      $result = (new $class())->$method($params);
    } else {
      $result = call_user_func($handler, $params);
    }

    if ($result instanceof Response) {
      $result->send();
    }
  }
}
