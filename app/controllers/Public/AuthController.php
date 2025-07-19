<?php

require_once './app/helpers/CsrfHelper.php';

Class AuthController extends Controller
{
    /**
     * Zpacování registrace uživatele
     *
     * @return void
     */
    public function register(): void
    {
        $userModel = $this->model('User');
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            $firstName = trim($_POST['first_name'] ?? '');
            $secondName = trim($_POST['second_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $antiSpam = $_POST['anti_spam'] ?? '';
            $password = trim($_POST['password'] ?? '');
            $passwordAgain = trim($_POST['password_again'] ?? '');

            if (empty($firstName) || empty($secondName) || empty($email) || empty($antiSpam) || empty($password) || empty($passwordAgain)) {
                $errors[] = "Všechna pole musí být vyplněna.";
            }

            if ($antiSpam != date('Y')) {
                $errors[] = "Špatně vyplněný antispam.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Neplatný formát emailu.";
            } elseif ($userModel->checkEmail($email)) {
                $errors[] = "Tento email je již zaregistrovaný.";
            }

            if ($password !== $passwordAgain) {
                $errors[] = "Hesla se neshodují.";
            }

            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                if ($userModel->register($firstName, $secondName, $email, $hashedPassword)) {
                    Url::redirectUrl("/auth/login");
                    return; // Zastaví další vykonávání
                } else {
                    $errors[] = "Registrace selhala. Zkuste to znovu.";
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("public/auth/register", [
            'title' => "Registrace",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }

    public function login():void
    {
        $csrfToken = CsrfHelper::generateToken();

        $this->view("public/auth/login", ['title' => "Přihlášení",
//            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }
}