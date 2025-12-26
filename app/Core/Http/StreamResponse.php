<?php

declare(strict_types=1);

namespace App\Core\Http;

final class StreamResponse extends Response
{
  public function __construct(callable $stream) {}

  public function send(): void
  {
    $this->sendHeaders();
    ($this->stream)();
  }
}
