<?php


class App {
    protected mixed $controller = 'HomeController';
    protected mixed $method = 'index';
    protected array $params = [];
    protected mixed $controllerFolder = 'Public';

    // Definice rout (včetně dynamických)
    protected array $routes = [
        // Veřejná část
        'register' => ['controller' => 'AuthController', 'method' => 'register', 'folder' => 'Public'],
        'login' => ['controller' => 'AuthController', 'method' => 'login', 'folder' => 'Public'],
        'logout' => ['controller' => 'AuthController', 'method' => 'logout', 'folder' => 'Public'],

        // Admin část
        'admin' => ['controller' => 'HomeController', 'method' => 'index', 'folder' => 'Admin'],
        'admin/students/create' => ['controller' => 'StudentController', 'method' => 'create', 'folder' => 'Admin'],
        'admin/students/students' => ['controller' => 'StudentController', 'method' => 'students', 'folder' => 'Admin'],
        'admin/students/student/{id}' => ['controller' => 'StudentController', 'method' => 'student', 'folder' => 'Admin'],
        'admin/students/delete{id}' => ['controller' => 'StudentController', 'method' => 'student', 'folder' => 'Admin'],
    ];

    public function __construct() {
        $url = $this->parseUrl();
        $urlPath = implode('/', $url);

        // 1. Zkus najít přesnou shodu
        if (isset($this->routes[$urlPath])) {
            $route = $this->routes[$urlPath];
            $this->controller = $route['controller'];
            $this->method = $route['method'];
            $this->controllerFolder = $route['folder'];
            $url = [];
        } else {
            // 2. Zkus najít dynamickou routu
            foreach ($this->routes as $routePattern => $routeConfig) {
                $patternParts = explode('/', $routePattern);
                $urlParts = $url;

                if (count($patternParts) !== count($urlParts)) continue;

                $isMatch = true;
                $params = [];

                foreach ($patternParts as $i => $part) {
                    if (preg_match('/^{\w+}$/', $part)) {
                        $params[] = $urlParts[$i];
                    } elseif ($part !== $urlParts[$i]) {
                        $isMatch = false;
                        break;
                    }
                }

                if ($isMatch) {
                    $this->controller = $routeConfig['controller'];
                    $this->method = $routeConfig['method'];
                    $this->controllerFolder = $routeConfig['folder'];
                    $this->params = $params;
                    $url = [];
                    break;
                }
            }

            // 3. Fallback logika, pokud žádná routa nesedí
            if (!empty($url)) {
                if (isset($url[0])) {
                    $potentialController = ucfirst($url[0]) . 'Controller';

                    if (file_exists("app/controllers/Public/{$potentialController}.php")) {
                        $this->controller = $potentialController;
                        $this->controllerFolder = 'Public';
                        unset($url[0]);
                    } elseif (file_exists("app/controllers/Admin/{$potentialController}.php")) {
                        $this->controller = $potentialController;
                        $this->controllerFolder = 'Admin';
                        unset($url[0]);
                    }
                }

                if (isset($url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                }

                $this->params = $url ? array_values($url) : [];
            }
        }

        require_once "app/controllers/{$this->controllerFolder}/{$this->controller}.php";

        $this->controller = new $this->controller;

        // Zavolej metodu s parametry
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl(): array {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['home', 'index'];
    }
}