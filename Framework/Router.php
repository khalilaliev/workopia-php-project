<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
  protected $routes = [];

  public function register_method(string $method, string $uri, string $action): void
  {
    list($controller, $controller_method) = explode('@', $action);


    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'controller_method' => $controller_method
    ];
  }

  /* Add a GET route */
  public function get(string $uri, string $controller): void
  {
    $this->register_method('GET', $uri, $controller);
  }

  // /* Add a POST route */
  // public function post(string $uri, string $controller): void
  // {
  //   $this->register_method('POST', $uri, $controller);
  // }

  // /* Add a PUT route */
  // public function put(string $uri, string $controller): void
  // {
  //   $this->register_method('PUT', $uri, $controller);
  // }

  // /* Add a DELETE route */
  // public function delete(string $uri, string $controller): void
  // {
  //   $this->register_method('DELETE', $uri, $controller);
  // }

  /* Route the request */
  public function route(string $uri, string $method): void
  {
    foreach ($this->routes as $route) {
      if ($route['uri'] === $uri && $route['method'] === $method) {
        $controller = 'App\\Controllers\\' . $route['controller'];
        $controller_method = $route['controller_method'];
        $controller_instance = new $controller();
        $controller_instance->$controller_method();
        return;
      }
    }
    ErrorController::not_found();
  }
}
