<?php

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    protected $controllerFolder = 'Public';

    public function __construct() {
        $url = $this->parseUrl();

        if (isset($url[0])) {
            $potentialController = ucfirst($url[0]) . 'Controller';

            // Zkus Public
            if (file_exists('app/controllers/Public/' . $potentialController . '.php')) {
                $this->controller = $potentialController;
                $this->controllerFolder = 'Public';
                unset($url[0]);
            }
            // Zkus Admin
            elseif (file_exists('app/controllers/Admin/' . $potentialController . '.php')) {
                $this->controller = $potentialController;
                $this->controllerFolder = 'Admin';
                unset($url[0]);
            }
        }

        // Načtení správného controlleru podle složky
        require_once 'app/controllers/' . $this->controllerFolder . '/' . $this->controller . '.php';

        $this->controller = new $this->controller;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

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