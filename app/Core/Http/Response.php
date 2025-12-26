<?php

declare(strict_types=1);

namespace App\Core\Http;

abstract class Response
{
  protected int $status = 200;
  protected array $headers = [];

  public function status(int $code): static
  {
    $this->status = $code;
    return $this;
  }

  public function header(string $key, string $value): static
  {
    $this->headers[$key] = $value;
    return $this;
  }

  protected function sendHeaders(): void
  {
    http_response_code($this->status);

    foreach ($this->headers as $key => $value) {
      header("{$key}: {$value}");
    }
  }

  abstract public function send(): void;
}
