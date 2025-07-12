<?php

require_once './app/helpers/CsrfHelper.php';

Class AuthController extends Controller {
    public function register(): void
    {
        $userModel = $this->model('User');

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            $first_name = trim($_POST['first_name']);
            $second_name = trim($_POST['second_name']);
            $email = strtolower(trim($_POST['email']));
            $anti_spam = $_POST['anti_spam'];
            $password = $_POST['password'];
            $password_again = $_POST['password_again'];
            $role = "ROLE_USER";

            if ($anti_spam != date('Y')) {
                $errors[] = "Špatně vyplněný Anti Spam";
            }

            if ($password != $password_again) {
                $errors[] = "Hesla se neshodují";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Neplatný formát emailu";
            }

            if ($userModel->checkEmail($email)) {
                $errors[] = "Tento Email je již zaregistrovaný";
            }

            if (empty($errors)) {
                $userModel->register($first_name, $second_name, $email, password_hash($password, PASSWORD_DEFAULT), $role);

                Url::redirectUrl("/");
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("auth/register", ['title' => "Registrace",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }

    public function login(): void
    {
        $userModel = $this->model('User');

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // CSRF
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            $log_email = strtolower(trim($_POST['log_email']));
            $log_password = trim($_POST['log_password']);

            if (empty($log_email) || empty($log_password)) {
                $errors[] = "Vyplňte prosím všechny údaje.";
            } elseif (!filter_var($log_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Neplatný formát emailu";
            }

            if (empty($errors)) {
                $user = $userModel->login($log_email, $log_password);

                if ($user) {
                    // Fixation attack defend More information:
                    // https://owasp.org/www-community/attacks/Session_fixation
                    session_regenerate_id(true);

                    $_SESSION['id'] = $user['id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['second_name'] = $user['second_name'];
                    $_SESSION['role'] = $user['role'];

                    URL::redirectUrl("/");
            } else {
                    $errors[] = "Neplatné přístupové údaje";
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("auth/login", ['title' => "Přihlášení",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }
}