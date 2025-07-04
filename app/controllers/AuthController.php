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

                URL::redirectUrl("/");
            } else {
                $errors[] = "Neplatné přístupové údaje.";
            }
        }
        $this->view("auth/signin", ['title' => "Přihlášení",
                                        'errors' => $errors]);
    }

    public function logout() {
        // Initialize the session.
        // If you are using session_name("something"), don't forget it now!
        session_start();

        // Unset all of the session variables.
        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        // Redirect to login page or homepage
        URL::redirectUrl("/");
    }
}