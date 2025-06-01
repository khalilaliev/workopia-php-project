<?php

namespace App\Controllers;

class ErrorController
{

  public static function not_found(string $message = 'Resource not found'): void
  {
    http_response_code(404);
    load_view('error', [
      'status' => '404',
      'message' => $message
    ]);
  }
  public static function not_authorized(string $message = 'Not authorized'): void
  {
    http_response_code(403);
    load_view('error', [
      'status' => '403',
      'message' => $message
    ]);
  }
}
