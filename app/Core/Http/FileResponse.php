<?php

declare(strict_types=1);

namespace App\Core\Http;

final class FileResponse extends Response
{
  public function __construct(private string $path) {}

  public function send(): void
  {
    if (!is_file($this->path)) {
      http_response_code(404);
      return;
    }

    $this->header(
      'Content-Type',
      mime_content_type($this->path) ?: 'application/octet-stream'
    );

    $this->sendHeaders();
    readfile($this->path);
  }
}
