<?php

namespace App\Classes;

class RequestHandler
{
    protected array $routes = [];

   public function __construct(protected array $request, protected array $server)
   {
   }

    public function registerRoute(string $route, callable $callback): void
    {
        $this->routes[] = ['path' => trim($route, '/'), 'callback' => $callback];
    }

    public function handle(): void
    {
        foreach ($this->routes as $route) {
            if ( preg_match('/' . $route['path'] . '/', trim($this->server['PATH_INFO']))) {
                header('Content-Type: application/json');
                echo $route['callback']($this->request);
            }
        }
    }
}