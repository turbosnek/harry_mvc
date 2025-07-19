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