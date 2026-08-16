<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Check if controller file exists
        if (isset($url[0]) && file_exists('../app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        } else if (isset($url[0])) {
            // Handle 404 - controller not found
            // For now, let it fall back or we can specifically set a 404 controller
        }

        require_once '../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Check if method exists in controller
        if (isset($url[1])) {
            $rawMethod = $url[1];
            $camelMethod = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $rawMethod))));
            $snakeMethod = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $rawMethod));

            if (method_exists($this->controller, $rawMethod)) {
                $this->method = $rawMethod;
                unset($url[1]);
            } elseif (method_exists($this->controller, $camelMethod)) {
                $this->method = $camelMethod;
                unset($url[1]);
            } elseif (method_exists($this->controller, $snakeMethod)) {
                $this->method = $snakeMethod;
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        // Call callback
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
