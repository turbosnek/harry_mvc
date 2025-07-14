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

            if (empty($first_name) or empty($second_name) or empty($age) or empty($life) or empty($college)) {
                $errors[] = "Všechna pole musí být vyplněna";
            }

            if ($age < 10) {
                $errors[] = "Minimální věk pro studenta je 10 let.";
            }

            if (empty($errors)) {
                if ($studentModel->create($first_name, $second_name, $age, $life, $college)) {
                    Url::redirectUrl("/admin/");
                } else {
                    $errors[] = "Přidání nového studenta selhalo. Zkuste to prosím znovu.";
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/create", ['title' => "Administrace - Nový žák školy",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
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
            $student = $studentModel->getStudent($id, "id, first_name, second_name, age, life, college");

            if (!$student) {
                $errors[] = "Student s tímto ID neexistuje.";
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

        // Validace ID ještě před voláním modelu
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            $errors[] = "Neplatné ID studenta.";
            $student = null;
        } else {
            $student = $studentModel->getStudent($id, "id, first_name, second_name");
            if (empty($student)) {
                $errors[] = "Student s tímto ID neexistuje.";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            // Pokud nejsou žádné chyby, pokračuj v mazání
            if (empty($errors)) {
                if ($studentModel->deleteStudent($id)) {
                    Url::redirectUrl("/student/students");
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
}