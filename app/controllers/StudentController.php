<?php

require_once './app/helpers/CsrfHelper.php';

Class StudentController extends Controller
{
    /**
     * Zpracování přidání nového žáka do systému
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
            }

            $first_name = trim($_POST['first_name']);
            $second_name = trim($_POST['second_name']);
            $age = $_POST['age'];
            $life = trim($_POST['life']);
            $college = trim($_POST['college']);

            if (empty($first_name) or empty($second_name) or empty($age) or empty($life) or empty($college)) {
                $errors[] = "Všechna pole musí být vyplněna";
            }

            if ($age < 10) {
                $errors[] = "Minimální věk pro studenta je 10 let.";
            }

            if (empty($errors)) {
                if ($studentModel->create($first_name, $second_name, $age, $life, $college)) {
                    Url::redirectUrl("/admin/");
                } else {
                    $errors[] = "Přidání nového studenta selhalo. Zkuste to prosím znovu.";
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/create", ['title' => "Administrace - Nový žák školy",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }
}