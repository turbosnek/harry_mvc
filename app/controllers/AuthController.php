<?php

Class AuthController extends Controller {
    public function register(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $first_name = trim($_POST['first_name']);
            $second_name = trim($_POST['second_name']);
            $email = trim($_POST['email']);
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

            if (empty($errors)) {
                $userModel = $this->model('User');
                $userModel->register($first_name, $second_name, $email, password_hash($password, PASSWORD_DEFAULT), $role);

                Url::redirectUrl("/");
            }
        }

        $this->view("auth/register", ['title' => "Registrace",
            'errors' => $errors]);
    }
}