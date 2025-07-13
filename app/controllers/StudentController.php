<?php

require_once './app/helpers/CsrfHelper.php';

Class StudentController extends Controller {
    /**
     * Vytvoří studenta
     *
     * @return void
     */
    public function createStudent(): void {
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
                $errors[] = "Prosím, vyplňte všechny údaje";
            }

            if ($age < 10) {
                $errors[] = "Minimální věk pro studenta je 10 Let.";
            }

            if (empty($errors)) {
                $studentModel->create($first_name, $second_name, $age, $life, $college);

                Url::redirectUrl("/student/students");
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/create", ['title' => "Administrace - Nový žák školy",
            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }

    /**
     * Získá všechny studenty
     *
     * @return void
     */
    public function students(): void
    {
        $studentModel = $this->model('Student');

        $errors = [];

        $students = $studentModel->getAllStudents("id, first_name, second_name");

        if (empty($students)) {
            $errors[] = "Žádní žáci nebyli nalezeni";
        }

        $this->view("admin/students/students", ["title" => "Administrace - Seznam žáků školy",
            'errors' => $errors,
            'students' => $students]);
    }

    /**
     * Získá jednoho studenta
     *
     * @param int $id - ID Studenta
     *
     * @return void
     */
    public function student(int $id): void
    {
        $studentModel = $this->model('Student');

        $errors = [];

        $student = $studentModel->getStudent($id, "id, first_name, second_name, age, life, college");

        if (empty($student)) {
            $errors[] = "Student s tímto ID neexistuje";
        }

        $this->view("admin/students/student", ["title" => "Administrace - Informace o žákovi",
            'errors' => $errors,
            'student' => $student]);
    }

    /**
     * Maže žáka ze systému
     *
     * @param int $id - ID žáka
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $studentModel = $this->model('Student');

        $errors = [];

        $student = $studentModel->getStudent($id, "id, first_name, second_name");

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (!isset($_POST['csrf_token']) || !CsrfHelper::validateToken($_POST['csrf_token'])) {
                $errors[] = "Neplatný CSRF token. Zkuste to prosím znovu.";
            }

            if (!filter_var($id, FILTER_VALIDATE_INT)) {
                $errors[] = "Neplatné ID studenta.";
            } else {
                if (empty($student)) {
                    $errors[] = "Student s tímto ID neexistuje.";
                } else {
                    if ($studentModel->deleteStudent($id)) {
                        Url::redirectUrl("/student/students");
                    } else {
                        $errors[] = "Chyba při mazání studenta.";
                    }
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/delete", ['title' => "Administrace - Smazaní žáka",
            'errors' => $errors,
            'student' => $student,
            'csrfToken' => $csrfToken]);
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

        $student = $studentModel->getStudent($id, "id, first_name, second_name, age, life, college");

        if ($student) {
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
                    $errors[] = "Prosím, vyplňte všechny údaje";
                }

                if (!filter_var($age, FILTER_VALIDATE_INT)) {
                    $errors[] = "Věk musí být číslo.";
                } elseif ((int)$age < 10) {
                    $errors[] = "Minimální věk pro studenta je 10 let.";
                }

                if (empty($errors)) {
                    $studentModel->updateStudent($first_name, $second_name, $age, $life, $college, $id);

                    Url::redirectUrl("/student/student/" . $id);
                }
            }
        }

        $csrfToken = CsrfHelper::generateToken();

        $this->view("admin/students/edit", ['title' => "Administrace - Aktualizace informací o žákovi",
            'errors' => $errors,
            'student' => $student,
            'csrfToken' => $csrfToken]);
    }
}