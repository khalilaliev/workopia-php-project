<?php

class Router
{
  protected $routes = [];

  public function register_method(string $method, string $uri, string $controller): void
  {
    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller
    ];
  }

  /* Add a GET route */
  public function get(string $uri, string $controller): void
  {
    $this->register_method('GET', $uri, $controller);
  }

  /* Add a POST route */
  public function post(string $uri, string $controller): void
  {
    $this->register_method('POST', $uri, $controller);
  }

  /* Add a PUT route */
  public function put(string $uri, string $controller): void
  {
    $this->register_method('PUT', $uri, $controller);
  }

  /* Add a DELETE route */
  public function delete(string $uri, string $controller): void
  {
    $this->register_method('DELETE', $uri, $controller);
  }

  public function error_page(int $http_code = 404): void
  {
    http_response_code($http_code);
    load_view("error/{$http_code}");
    exit();
  }

  /* Route the request */
  public function route(string $uri, string $method): void
  {
    foreach ($this->routes as $route) {
      if ($route['uri'] === $uri && $route['method'] === $method) {
        require base_path($route['controller']);
        return;
      }
    }
    $this->error_page();
  }
}
