<?php
namespace app\routes;

class ApiRouter {
  private $routes = [];

  public function get($route, $action) {
    $this->routes['GET'][$route] = $action;
  }

  public function post($route, $action) {
    $this->routes['POST'][$route] = $action;
  }

  /*public function put($route, $action) {
    $this->routes['PUT'][$route] = $action;
  }*/

  /*public function delete($route, $action) {
    $this->routes['DELETE'][$route] = $action;
  }*/

  public function resolve($uri, $method)
  {
    // Iterate through the routes for the given method (GET, POST, etc.)
    foreach ($this->routes[$method] as $route => $action) {
      // Convert dynamic route parameters (e.g., /comments/{productId}) into a regular expression
      $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $route);

      // Match the URI to the route pattern
      if (preg_match("#^$routePattern$#", $uri, $matches)) {
        // If there are dynamic parameters, pass them to the controller method
        if (is_array($action) && class_exists($action[0]) && method_exists($action[0], $action[1])) {
          $controller = new $action[0]();

          // Extract the dynamic parameters from the URI matches and pass them to the controller
          $params = array_filter($matches, function($key) {
            return !is_int($key); // Keep only named parameters (e.g., productId)
          }, ARRAY_FILTER_USE_KEY);

          // Call the method with the parameters
          call_user_func_array([$controller, $action[1]], $params);
          return;
        }
      }
    }

    // If no matching route was found, return a 404 error
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
  }
}
