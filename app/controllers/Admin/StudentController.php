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
                    if ($studentModel->createStudent($firstName, $secondName, $age, $life, $college)) {
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

        $student = $studentModel->getStudent($id, ['id', 'first_name', 'second_name', 'age', 'life', 'college']);

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
}