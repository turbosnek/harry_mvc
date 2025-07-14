<?php

require_once './app/helpers/CsrfHelper.php';

Class UserController extends Controller {
    public function users(): void
    {
        $userModel = $this->model('User');

        $errors = [];

        $users = $userModel->getAllUsers(["id, first_name, second_name"]);

        if (empty($users)) {
            $errors[] = "Žádní uživatelé nebyli nalezeni";
        }

        $this->view("admin/users/users", ["title" => "Administrace - Seznam uživatelů",
            'errors' => $errors,
            'users' => $users]);
    }
}