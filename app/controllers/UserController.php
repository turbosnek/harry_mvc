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
        $users = $userModel->getAllStudents(['id', 'first_name', 'second_name, email']);

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
}