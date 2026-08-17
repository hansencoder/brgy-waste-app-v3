<?php
class Controller {
    public function model($model) {
        $path = dirname(__DIR__) . '/Models/' . $model . '.php';
        if (file_exists($path)) {
            require_once $path;
            return new $model();
        }
        die('Model does not exist: ' . htmlspecialchars($model));
    }

    public function view($view, $data = []) {
        $path = dirname(__DIR__) . '/Views/' . $view . '.php';
        if (file_exists($path)) {
            require_once $path;
        } else {
            die('View does not exist: ' . htmlspecialchars($view));
        }
    }
}
