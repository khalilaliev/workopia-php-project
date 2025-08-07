<?php

namespace Framework;

use App\Controllers\ErrorController;
use Framework\middleware\Authorize;

class Router
{
  protected $routes = [];

  public function register_method(string $method, string $uri, string $action, array $middleware): void
  {
    list($controller, $controller_method) = explode('@', $action);


    $this->routes[] = [
      'method' => $method,
      'uri' => $uri,
      'controller' => $controller,
      'controller_method' => $controller_method,
      'middleware' => $middleware
    ];
  }

  /* Add a GET route */
  public function get(string $uri, string $controller, array $middleware = []): void
  {
    $this->register_method('GET', $uri, $controller, $middleware);
  }

  /* Add a POST route */
  public function post(string $uri, string $controller, array $middleware = []): void
  {
    $this->register_method('POST', $uri, $controller, $middleware);
  }

  /* Add a PUT route */
  public function put(string $uri, string $controller, array $middleware = []): void
  {
    $this->register_method('PUT', $uri, $controller, $middleware);
  }

  /* Add a DELETE route */
  public function delete(string $uri, string $controller, array $middleware = []): void
  {
    $this->register_method('DELETE', $uri, $controller, $middleware);
  }

  /* Route the request */
  public function route(string $uri): void
  {
    $request_method = $_SERVER['REQUEST_METHOD'];

    if ($request_method === 'POST' && isset($_POST['_method'])) {
      // Override the request method 

      $request_method = strtoupper($_POST['_method']);
    }

    foreach ($this->routes as $route) {

      // Split curr URI segment into array

      $uri_segment = explode('/', trim($uri, '/'));

      // Split the rout URI  into segment

      $route_segment = explode('/', trim($route['uri'], '/'));

      $is_match = true;

      if (count($uri_segment) === count($route_segment) && strtoupper($route['method'] === $request_method)) {
        $params = [];

        $is_match = true;

        for ($i = 0; $i < count($uri_segment); $i++) {
          if ($route_segment[$i] !== $uri_segment[$i] && !preg_match('/\{(.+?)\}/', $route_segment[$i])) {
            $is_match = false;
            break;
          }
          if (preg_match('/\{(.+?)\}/', $route_segment[$i], $matches)) {
            $params[$matches[1]] = $uri_segment[$i];
          }
        }

        if ($is_match) {

          foreach ($route['middleware'] as $middleware) {
            (new Authorize())->handle($middleware);
          }

          $controller = 'App\\Controllers\\' . $route['controller'];
          $controller_method = $route['controller_method'];
          $controller_instance = new $controller();
          $controller_instance->$controller_method($params);
          return;
        }
      }
    }
    ErrorController::not_found();
  }
}
