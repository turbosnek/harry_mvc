<?php

class App
{
    protected $controller = "HomeController";
    protected $method = "";
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // ✅ Custom URL mappings
        $routes = [
            "admin" => "AdminHomeController",
            "login" => "AuthController",
            "signin" => "LoginController",
        ];

        // If URL has a controller, map it
        $controllerKey = $url[0] ?? "home"; // Default to "home"
        $controllerName = $routes[$controllerKey] ?? ucfirst($controllerKey) . "Controller";
        $controllerFile = "../app/controllers/" . $controllerName . ".php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $controllerName;
            unset($url[0]);
        } else {
            echo "Controller not found: " . $controllerName;
            exit;
        }

        // If URL has a method, set it
        if (!empty($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } else {
            // 🔥 If no method is provided in the URL, check for a default method
            if (empty($this->method)) {
                $defaultMethods = [
                    "AuthController" => "login",
                    "RegistrationController" => "register",
                ];

                $this->method = $defaultMethods[get_class($this->controller)] ?? "index";
            }

            if (!method_exists($this->controller, $this->method)) {
                echo "Method not found: " . get_class($this->controller) . "::" . $this->method;
                exit;
            }
        }

        $this->params = $url ? array_values($url) : [];

        if (method_exists($this->controller, $this->method)) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            echo "Method not found: " . get_class($this->controller) . "::" . $this->method;
            exit;
        }
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode("/", filter_var(rtrim($_GET['url'], "/"), FILTER_SANITIZE_URL));
        }
        return [];
    }
}