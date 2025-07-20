<?php

require_once './app/helpers/CsrfHelper.php';

Class StudentController extends Controller
{
    /**
     * Zpracování přidání nového studenta do systému
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
            } else {
                $firstName = trim($_POST['first_name']);
                $secondName = trim($_POST['second_name']);
                $age = (int) $_POST['age'];
                $life = trim($_POST['life']);
                $college = trim($_POST['college']);
                $profileImage = $_FILES['profile_image'] ?? null;


                // Zkontrolujeme, jestli jsou všechna pole vyplněna. pokud ne, uložíme chybu do proměnné
                if (empty($firstName) or empty($secondName) or empty($age) or empty($life) or empty($college)) {
                    $errors[] = "Všechna pole musí být vyplněna";
                }

                // Zkontrolujeme, jestli zadané číslo je číslo a jestli je věk menší než 10. Pokud neplatí, uložíme chybu do proměnné
                if (!filter_var($age, FILTER_VALIDATE_INT)) {
                    $errors[] = "Věk musí být číslo";
                } elseif ($age < 10) {
                    $errors[] = "Minimální věk pro studenta je 10 let";
                }

                // Pokud není žádný error, přidáme studenta do databáze a přesměrujeme na hlavní stránku administrace, jinak uložíme chybu do proměnné
                if (empty($errors)) {
                    if ($studentModel->createStudent($firstName, $secondName, $age, $life, $college, $profileImage)) {
                        Url::redirectUrl("/admin/students/students");
                        return;
                    } else {
                        $errors[] = "Přidání nového studenta selhalo. Zkuste to prosím znovu";
                    }
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/create", [
            'title' => "Administrace - Nový žák školy",
            'errors' => $errors,
            'csrfToken' => $csrfToken
        ]);
    }

    /**
     * Získá všechny studenty z databáze
     *
     * @return void
     */
    public function students(): void
    {
        $studentModel = $this->model('Student');

        $errors = [];

        // Volání modelu s požadovanými sloupci
        $students = $studentModel->getAllStudents(['id', 'first_name', 'second_name']);

        if ($students === false) {
            $errors[] = "Nastala chyba při načítání studentů z databáze";
            $students = []; // Aby měla view co zpracovávat
        } elseif (empty($students)) {
            $errors[] = "Žádní žáci nebyli nalezeni";
        }

        $this->view('admin/students/students', [
            'title' => "Administrace - Seznam studentů",
            'errors' => $errors,
            'students' => $students
            ]);
    }

    /**
     * Získá jednoho studenta
     *
     * @param int $id - ID studenta
     *
     * @return void
     */
    public function student(int $id): void
    {
        $studentModel = $this->model('Student');
        $errors = [];

        // Volání modelu s požadovanými sloupci
        $student = $studentModel->getStudent($id, ['id', 'first_name', 'second_name', 'age', 'life', 'college', 'profile_image']);

        // Když student neexistuje, uložíme chybu do proměnné
        if (!$student) {
            $nonBreakingSpace = "\u{00A0}"; // Unicode znak nezlomitelné mezery
            $errors[] = "Student s{$nonBreakingSpace}tímto{$nonBreakingSpace}ID neexistuje.";
        }

        $this->view("admin/students/student", [
            "title" => "Administrace - Informace o žákovi",
            "errors" => $errors,
            "student" => $student
        ]);
    }

    /**
     * Zpracovává smazání studenta ze systému
     *
     * @param int $id - ID studenta
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $studentModel = $this->model('Student');
        $errors = [];
        $student = null;


        // Zkontrolujeme, jestli je ID studenta platné. Získámé potřebné sloupce z databáze, které budeme potřebovat. pokud nesouhlasí, uložíme chybu do proměnné
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            $errors[] = "Neplatné ID studenta.";
        } else {
            $student = $studentModel->getStudent($id, ['id', 'first_name', 'second_name']);
            if (empty($student)) {
                $errors[] = "Student s tímto ID neexistuje.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // Zkontrolujeme, jestli je správně nastavený CSRF Token, pokud ne, uložíme chybu do proměnné
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            } else {
                // Pokud není žádná chyba, smažeme studenta a přesměrujeme na danou URL. Pokud je chyba, tak tu uložíme do proměnné
                if (empty($errors)) {
                    if ($studentModel->deleteStudent($id)) {
                        Url::redirectUrl("/admin/students/students");
                        return;
                    } else {
                        $errors[] = "Chyba při mazání studenta.";
                    }
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/delete", [
            'title' => "Administrace - Smazání žáka",
            'errors' => $errors,
            'student' => $student,
            'csrfToken' => $csrfToken
        ]);
    }

    /**
     * Zpracovává editaci informací o studentovi
     *
     * @param $id
     *
     * @return void
     */
    public function edit($id): void
    {
        $studentModel = $this->model('Student');
        $errors = [];

        // Získáme všechny sloupečky z DB, které potřebujeme
        $student = $studentModel->getStudent($id, ['id', 'first_name', 'second_name', 'age', 'life', 'college']);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // Zkontrolujeme, jestli je správně nastavený CSRF Token, pokud ne, uložíme chybu do proměnné
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
                // Pokud je CSRF Token nastavený, přejdeme k validaci formuláře
            } else {
                $first_name = trim($_POST['first_name']);
                $second_name = trim($_POST['second_name']);
                $life = trim($_POST['life']);
                $college = trim($_POST['college']);
                $age_input = $_POST['age'];

                // Zkontrolujeme, jsetli jsou všechna pole ve formuláři vyplněna. pokud ne, uložíme chybu do proměnné
                if ($first_name === '' || $second_name === '' || $life === '' || $college === '' || $age_input === '') {
                    $errors[] = "Prosím, vyplňte všechny údaje";
                }

                // Zkontrolujeme, jestli je věk číslo nebo jestli není menší jak 10. Pokud to tak je, uložíme chybu do promněnné
                if (!filter_var($age_input, FILTER_VALIDATE_INT)) {
                    $errors[] = "Věk musí být číslo.";
                } else {
                    $age = (int) $age_input;
                    if ($age < 10) {
                        $errors[] = "Minimální věk pro studenta je 10 let.";
                    }
                }

                // Když není žádná chyba, aktualizujeme informace o studentovi. Když to selže, uložíme chybu do promněnné
                if (empty($errors)) {
                    $success = $studentModel->updateStudent($first_name, $second_name, $age, $life, $college, $id);
                    if ($success) {
                        Url::redirectUrl("/admin/students/student/" . $id);
                        return;
                    } else {
                        $errors[] = "Aktualizace studenta selhala. Zkuste to prosím znovu.";
                    }
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/edit", [
            'title' => "Administrace - Aktualizace informací o žákovi",
            'errors' => $errors,
            'student' => $student,
            'csrfToken' => $csrfToken
        ]);
    }
}