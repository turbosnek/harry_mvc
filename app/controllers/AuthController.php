<?php

Class AuthController extends Controller {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $log_email = $_POST['log-email'];
            $log_password = $_POST['log-password'];

            $user = $this->userModel->login($log_email, $log_password);

            if ($user) {
                // Fixation attack defend More information:
                // https://owasp.org/www-community/attacks/Session_fixation
                session_regenerate_id(true);

                $_SESSION['id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['second_name'] = $user['second_name'];
                $_SESSION['role'] = $user['role'];

                URL::redirectUrl("/Web/harry_mvc/registration");
            } else {
                $errors[] = "Neplatné přístupov= údaje.";
            }
        }
        $this->view("auth/signin", ['title' => "Přihlášení",
                                        'errors' => $errors]);
    }

//    public function login() {
//        $errors = [];
//
//        if ($_SERVER['REQUEST_METHOD'] === "POST") {
//            $log_email = $_POST['log-email'];
//            $log_password = $_POST['log-password'];
//
//            $user = new User();
//
//            if (empty($errors)) {
//                if ($user->login($log_email, $log_password)) {
//                    $id = $user->getUserId($log_email);
//
//                    // Fixation attack defend More information:
//                    // https://owasp.org/www-community/attacks/Session_fixation
//                    session_regenerate_id(true);
//
//                    // Nastavení, že je uživatel přihlášený
//                    $_SESSION['is_logged_in'] = true;
//                    // Nastavení ID uživatele
//                    $_SESSION['logged_in_user_id'] = $id;
//
////                    $_SESSION["id"] = $user["id"];
////                    $_SESSION['first_name'] = $user['first_name'];
////                    $_SESSION['second_name'] = $user['second_name'];
////                    $_SESSION['role'] = $user['role'];
//
//                    URL::redirectUrl("/Web/harry_mvc/registration");
//                } else {
//                    $errors[] = "Neplatné uživatelské údaje. Zkuste to znovu";
//                }
//            }
//        }
//
//        $this->view("auth/signin", ['title' => "Přihlášení",
//                                        'errors' => $errors]);
//    }
}