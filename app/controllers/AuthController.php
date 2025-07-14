<?php

require_once './app/helpers/CsrfHelper.php';

Class AuthController extends Controller
{
    /**
     * Zpracování registrace uživatele
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

            $first_name = trim($_POST['first_name']);
            $second_name = trim($_POST['second_name']);
            $email = strtolower(trim($_POST['email']));
            $anti_spam = $_POST['anti_spam'];
            $password = trim($_POST['password']);
            $password_again = trim($_POST['password_again']);
            $role = "ROLE_USER";

            if (empty($first_name) or empty($second_name) or empty($email) or empty($anti_spam) or empty($password) or empty($password_again)) {
                $errors[] = "Všechna pole musí být vyplněna.";
            }

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
                if ($userModel->register($first_name, $second_name, $email, password_hash($password, PASSWORD_DEFAULT), $role)) {
                    Url::redirectUrl("/auth/login");
                } else {
                    $errors[] = "Registrace selhala. Zkuste to znovu.";
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("auth/register", ['title' => "Registrace",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }
}