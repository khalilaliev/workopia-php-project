<?php

namespace Framework;

class Session
{
  /**
   * Start the session
   * 
   * @return void
   */
  public static function start()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * Start a session key/value pair
   * 
   * @param string $key
   * @param mixed $value
   * @return void
   * 
   */
  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  /**
   * Get a session value by key
   * 
   * @param string $key
   * @param mixed $default
   * @return mixed
   * 
   */
  public static function get($key, $default = null)
  {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
  }

  /**
   * Check if Session key exist
   * 
   * @param string $key
   * @return boolean
   * 
   */
  public static function has($key)
  {
    return isset($_SESSION[$key]);
  }

  /**
   * Clear Session by key
   * 
   * @param string $key
   * @return void
   * 
   */
  public static function clear($key)
  {
    if (isset($_SESSION[$key])) {
      unset($_SESSION[$key]);
    }
  }

  /**
   * Clear all Session data 
   * 
   * @return void
   * 
   */
  public static function clear_all($key)
  {
    session_unset();
    session_destroy();
  }
}
