<?php

Class UserController extends Controller
{
    /**
     * Získá všechny uživatele z databáze a předá je do view
     *
     * @return void
     */
    public function users(): void
    {
        $userModel = $this->model('User');

        $errors = [];

        // Volání modelu s požadovanými sloupci
        $users = $userModel->getAllUsers(['id', 'first_name', 'second_name, email']);

        if ($users === false) {
            $errors[] = "Nastala chyba při načítání uživatelů z databáze.";
            $students = []; // Aby view měla co zpracovávat
        } elseif (empty($users)) {
            $errors[] = "Žádní uživatelé nebyli nalezeni.";
        }

        // Předání dat do view
        $this->view("admin/users/users", [
            "title" => "Administrace - Seznam uživatelů",
            "errors" => $errors,
            "users" => $users
        ]);
    }

    /**
     * Získá jednoho uživatele
     *
     * @param int $id - ID uživatele
     *
     * @return void
     */
    public function user(int $id): void
    {
        $userModel = $this->model('User');
        $errors = [];

        try {
            $user = $userModel->getUser($id, ['id', 'first_name', 'second_name', 'email', 'password', 'role']);

            if (!$user) {
                $nbsp = "\u{00A0}"; // Unicode znak nezlomitelné mezery
                $errors[] = "Uživatel s{$nbsp}tímto{$nbsp}ID neexistuje.";
            }

        } catch (InvalidArgumentException $e) {
            $errors[] = "Chybný požadavek: " . $e->getMessage();
            $user = null;
        }

        $this->view("admin/users/user", [
            "title" => "Administrace - Informace o uživateli",
            "errors" => $errors,
            "user" => $user
        ]);
    }
}