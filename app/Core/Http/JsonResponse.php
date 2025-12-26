<?php

declare(strict_types=1);

namespace App\Core\Http;

final class JsonResponse extends Response
{
  public function __construct(private mixed $data) {}

  public function send(): void
  {
    $this->header('Content-Type', 'application/json');
    $this->sendHeaders();

    echo json_encode(
      $this->data,
      JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE
    );
  }
}
