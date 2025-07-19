<?php

class App {
    protected mixed $controller = 'HomeController';
    protected mixed $method = 'index';
    protected array $params = [];
    protected mixed $controllerFolder = 'Public';

    // Tabulka rout
    protected array $routes = [
        // Veřejná část
        'login' => ['controller' => 'AuthController', 'method' => 'login', 'folder' => 'Public'],
        // Admin část
//        'admin/users/create' => ['controller' => 'UsersController', 'method' => 'create', 'folder' => 'Admin'],
    ];

    public function __construct() {
        $url = $this->parseUrl();
        $urlPath = implode('/', $url);

        // Zkontroluj, jestli URL odpovídá nějaké routě
        if (isset($this->routes[$urlPath])) {
            $route = $this->routes[$urlPath];
            $this->controller = $route['controller'];
            $this->method = $route['method'];
            $this->controllerFolder = $route['folder'];
            $url = []; // vyčistíme URL
        } else {
            // Pokud není v tabulce, použij klasické pravidlo
            if (isset($url[0])) {
                $potentialController = ucfirst($url[0]) . 'Controller';

                if (file_exists('app/controllers/Public/' . $potentialController . '.php')) {
                    $this->controller = $potentialController;
                    $this->controllerFolder = 'Public';
                    unset($url[0]);
                } elseif (file_exists('app/controllers/Admin/' . $potentialController . '.php')) {
                    $this->controller = $potentialController;
                    $this->controllerFolder = 'Admin';
                    unset($url[0]);
                }
            }

            if (isset($url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        require_once 'app/controllers/' . $this->controllerFolder . '/' . $this->controller . '.php';

        $this->controller = new $this->controller;

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['home', 'index'];
    }
}