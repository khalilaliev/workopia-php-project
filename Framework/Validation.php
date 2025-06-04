<?php

namespace Framework;

class Validation
{
  // Validate user
  public static function string(string $value, int $min = 1, int $max = INF): string
  {
    if (is_string($value)) {
      $value = trim($value);
      $length = strlen($value);
      return $length >= $min && $length <= $max;
    }
    return false;
  }

  // Validate email
  public static function email($value): mixed
  {
    $value = trim($value);

    return filter_var($value, FILTER_VALIDATE_EMAIL);
  }

  // Match value against another
  public static function match(string $value1, string $value2): bool
  {
    $value1 = trim($value1);
    $value2 = trim($value2);

    return $value1 === $value2;
  }
}
