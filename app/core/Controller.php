<?php
//
//
//class Controller {
//    public function model($model) {
//        require_once 'app/models/' . $model . '.php';
//        return new $model();
//    }
//
//    public function view($view, $data = []) {
//        require_once 'app/views/' . $view . '.php';
//    }
//}

class Controller {
    public function model($model) {
        // Cesty ke složkám
        $publicModelPath = 'app/models/Public/' . $model . '.php';
        $adminModelPath = 'app/models/Admin/' . $model . '.php';

        if (file_exists($publicModelPath)) {
            require_once $publicModelPath;
        } elseif (file_exists($adminModelPath)) {
            require_once $adminModelPath;
        } else {
            // Pokud model neexistuje ani v jedné složce
            die("Model '$model' nebyl nalezen.");
        }

        return new $model();
    }

    public function view($view, $data = []) {
        require_once 'app/views/' . $view . '.php';
    }
}