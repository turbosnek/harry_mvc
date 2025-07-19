<?php

require_once './app/helpers/CsrfHelper.php';

Class StudentController extends Controller
{
    /**
     * Zpracování přidání nového studenta do systému
     *
     * @return void
     */
    public function create(): void
    {
        $studentModel = $this->model('Student');
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            } else {
                $firstName = trim($_POST['first_name']);
                $secondName = trim($_POST['second_name']);
                $age = (int) $_POST['age'];
                $life = trim($_POST['life']);
                $college = trim($_POST['college']);

                // Zkontrolujeme, jestli jsou všechna pole vyplněna. pokud ne, uložíme chybu do proměnné
                if (empty($firstName) or empty($secondName) or empty($age) or empty($life) or empty($college)) {
                    $errors[] = "Všechna pole musí být vyplněna";
                }

                // Zkontrolujeme, jestli zadané číslo je číslo a jestli je věk menší než 10. Pokud neplatí, uložíme chybu do proměnné
                if (!filter_var($age, FILTER_VALIDATE_INT)) {
                    $errors[] = "Věk musí být číslo";
                } elseif ($age < 10) {
                    $errors[] = "Minimální věk pro studenta je 10 let";
                }

                // Pokud není žádný error, přidáme studenta do databáze a přesměrujeme na hlavní stránku administrace, jinak uložíme chybu do proměnné
                if (empty($errors)) {
                    if ($studentModel->createStudent($firstName, $secondName, $age, $life, $college)) {
                        Url::redirectUrl("/admin");
                        return;
                    } else {
                        $errors[] = "Přidání nového studenta selhalo. Zkuste to prosím znovu";
                    }
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/create", [
            'title' => "Administrace - Nový žák školy",
            'errors' => $errors,
            'csrfToken' => $csrfToken
        ]);
    }
}