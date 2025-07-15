<?php

require_once './app/helpers/CsrfHelper.php';

Class StudentController extends Controller
{
    /**
     * Zpracování přidání nového žáka do systému
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
            }

            $first_name = trim($_POST['first_name']);
            $second_name = trim($_POST['second_name']);
            $age = $_POST['age'];
            $life = trim($_POST['life']);
            $college = trim($_POST['college']);
            $profileImage = $_FILES['profile_image'] ?? null;

            if (empty($first_name) or empty($second_name) or empty($age) or empty($life) or empty($college)) {
                $errors[] = "Všechna pole musí být vyplněna";
            }

            if ($age < 10) {
                $errors[] = "Minimální věk pro studenta je 10 let.";
            }

            if (empty($errors)) {
                if ($studentModel->create($first_name, $second_name, $age, $life, $college, $profileImage)) {
                    Url::redirectUrl("/student/students/");
                    return;
                } else {
                    $errors[] = "Přidání nového studenta selhalo. Zkuste to prosím znovu.";
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
     * Získá všechny studenty z databáze a předá je do view
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
            $errors[] = "Nastala chyba při načítání studentů z databáze.";
            $students = []; // Aby view měla co zpracovávat
        } elseif (empty($students)) {
            $errors[] = "Žádní žáci nebyli nalezeni.";
        }

        // Předání dat do view
        $this->view("admin/students/students", [
            "title" => "Administrace - Seznam žáků školy",
            "errors" => $errors,
            "students" => $students
        ]);
    }

    /**
     * Získá jednoho studenta
     *
     * @param int $id
     *
     * @return void
     */
    public function student(int $id): void
    {
        $studentModel = $this->model('Student');

        $errors = [];

        try {
            $student = $studentModel->getStudent($id, "id, first_name, second_name, age, life, college, profile_image");

            if (!$student) {
                $nbsp = "\u{00A0}"; // Unicode znak nezlomitelné mezery
                $errors[] = "Student s{$nbsp}tímto{$nbsp}ID neexistuje.";
            }

        } catch (InvalidArgumentException $e) {
            $errors[] = "Chybný požadavek: " . $e->getMessage();
            $student = null;
        }

        $this->view("admin/students/student", [
            "title" => "Administrace - Informace o žákovi",
            "errors" => $errors,
            "student" => $student
        ]);
    }

    /**
     * Maže žáka ze systému
     *
     * @param int $id - ID žáka
     * @return void
     */
    public function delete(int $id): void
    {
        $studentModel = $this->model('Student');
        $errors = [];

        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            $errors[] = "Neplatné ID studenta.";
        } else {
            $student = $studentModel->getStudent($id, "id, first_name, second_name, profile_image");
            if (empty($student)) {
                $errors[] = "Student s tímto ID neexistuje.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            if (empty($errors)) {
                if ($studentModel->deleteStudent($id)) {
                    Url::redirectUrl("/student/students");
                    return;
                } else {
                    $errors[] = "Chyba při mazání studenta.";
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
     * Upravuje informace o žákovi v databázi
     *
     * @param $id
     *
     * @return void
     */
    public function edit($id): void
    {
        $studentModel = $this->model('Student');
        $errors = [];

        $student = $studentModel->getStudent($id, "id, first_name, second_name, age, life, college, profile_image");

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            $first_name = trim($_POST['first_name']);
            $second_name = trim($_POST['second_name']);
            $life = trim($_POST['life']);
            $college = trim($_POST['college']);
            $age_input = $_POST['age'];
            $profileImage = $_FILES['profile_image'] ?? null;

            if ($first_name === '' || $second_name === '' || $life === '' || $college === '' || $age_input === '') {
                $errors[] = "Prosím, vyplňte všechny údaje";
            }

            if (!filter_var($age_input, FILTER_VALIDATE_INT)) {
                $errors[] = "Věk musí být číslo.";
            } else {
                $age = (int) $age_input;
                if ($age < 10) {
                    $errors[] = "Minimální věk pro studenta je 10 let.";
                }
            }

            if (empty($errors)) {
                $success = $studentModel->updateStudent($first_name, $second_name, $age, $life, $college, $id, $profileImage);
                if ($success) {
                    Url::redirectUrl("/student/student/" . $id);
                    return;
                } else {
                    $errors[] = "Aktualizace studenta selhala. Zkuste to prosím znovu.";
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