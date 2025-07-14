<?php

require_once './app/helpers/CsrfHelper.php';

Class UserController extends Controller {
    /**
     * Získá všechny uživatele
     *
     * @return void
     */
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

    /**
     * Získá ionformace o uživateli
     *
     * @param int $id - ID uživatele
     *
     * @return void
     */
    public function user(int $id): void
    {
        $userModel = $this->model('User');

        $errors = [];

        $user = $userModel->getUser($id, ["id, first_name, second_name, email, password, role"]);

        if (empty($user)) {
            $errors[] = "Uživatel s tímto ID neexistuje";
        }

        $this->view("admin/users/user", ["title" => "Administrace - Informace o uživateli",
            'errors' => $errors,
            'user' => $user]);
    }
}