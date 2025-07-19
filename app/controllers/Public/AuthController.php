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
            } else {
                $firstName = trim($_POST['first_name'] ?? '');
                $secondName = trim($_POST['second_name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $antiSpam = $_POST['anti_spam'] ?? '';
                $password = trim($_POST['password'] ?? '');
                $passwordAgain = trim($_POST['password_again'] ?? '');

                // Zjistíme, jestli jsou všechna pole vyplněna. Pokud ne, uložíme chybu do proměnné
                if (empty($firstName) || empty($secondName) || empty($email) || empty($antiSpam) || empty($password) || empty($passwordAgain)) {
                    $errors[] = "Všechna pole musí být vyplněna.";
                }

                // Zjistíme, jestli je správně vyplněný anti spam. pokud ne, uložíme chybu do proměnné
                if ($antiSpam != date('Y')) {
                    $errors[] = "Špatně vyplněný antispam.";
                }

                // Zjistíme, jestli má email správný tvar a jestli už není náhodou email zaregistrovaný. Pokud to není pravda, uložíme chybu do promšnné
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Neplatný formát emailu.";
                } elseif ($userModel->checkEmail($email)) {
                    $errors[] = "Tento email je již zaregistrovaný.";
                }

                // Zjistíme, jestli se hesla shodují. pokud ne, uložíme chybu do proměnné
                if ($password !== $passwordAgain) {
                    $errors[] = "Hesla se neshodují.";
                }

                // Když nejsou žádné chyby, spustíme registraci
                if (empty($errors)) {
                    // Zahashujeme heslo
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Pokud registrace proběhla, přesměrujeme na danou stránku, pokud ne, uložíme chybu do proměnné
                    if ($userModel->register($firstName, $secondName, $email, $hashedPassword)) {
                        Url::redirectUrl("/auth/login");
                        return; // Zastaví další vykonávání
                    } else {
                        $errors[] = "Registrace selhala. Zkuste to znovu.";
                    }
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("public/auth/register", [
            'title' => "Registrace",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }

    /**
     * Zpracování přihlášení uživatele
     *
     * @return void
     */
    public function login(): void
    {
        $userModel = $this->model('User');

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // CSRF
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            } else {
                $logEmail = strtolower(trim($_POST['log_email']));
                $logPassword = trim($_POST['log_password']);

                // Zkontrolujeme, jestli jsou vyplněny všechny údaje a jesli je zadán správný tvar emailu. pokud ne, uložíme chybu do proměnné
                if (empty($logEmail) || empty($logPassword)) {
                    $errors[] = "Vyplňte prosím všechny údaje.";
                } elseif (!filter_var($logEmail, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Neplatný formát emailu";
                }

                // Pokud nejsou žádné chyby, provedeme přihlášení a nastavíme údaje uživateli do SESSIOM a přesměrujeme na zadanou URL
                if (empty($errors)) {
                    $user = $userModel->login($logEmail, $logPassword);

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
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("public/auth/login", [
            'title' => "Přihlášení",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }

    /**
     * Odhlášení uživatele
     *
     * @return void
     */
    public function logout(): void
    {
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