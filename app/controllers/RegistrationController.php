<?php

class RegistrationController extends Controller {
    
//    public function index() {
//        $this->view("registration/index", ["title" => "Registrace"]);
//    }

    public function register(): void {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $first_name = $_POST['first_name'];
            $second_name = $_POST['second_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password-again'];
            $anti_spam = $_POST['anti-spam'];
            $role = "ROLE_USER";


            $user = new User();

            if ($anti_spam != date('Y')) {
                $errors[] = "Špatně vyplněný anti spam.";
            }

            if ($user->checkUserEmail($email)) {
                $errors[] = "Tento email již existuje.";
            }

            if ($password != $password2) {
                $errors[] = "Hesla se neshodují.";
            }

            if (empty($errors)) {
                if ($user->createUser(htmlspecialchars($first_name), htmlspecialchars($second_name), htmlspecialchars($email), password_hash($password, PASSWORD_DEFAULT), $role)) {
                    URL::redirectUrl("/Web/harry_mvc/");
                } else {
                    $errors[] = "Registrace se nezdařila. Zkuste to prosím znovu";
                }
            }
        }
        $this->view("registration/index", ['title' => "Registrace",
                                                'errors' => $errors]);
    }
}