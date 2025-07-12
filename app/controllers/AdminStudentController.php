<?php

Class AdminStudentController extends Controller {
    private Student $studentModel;

    public function __construct() {
        $this->studentModel = new Student();
    }

    public function createStudent(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $first_name = $_POST['first_name'];
            $second_name = $_POST['second_name'];
            $age = $_POST['age'];
            $life = $_POST['life'];
            $college = $_POST['college'];

            $student = $this->studentModel->createStudent($first_name, $second_name, $age, $life, $college);

            if ($student) {
                Url::redirectUrl("/admin/students");
            } else {
                $errors[] = "Žáka se nepodařilo přidat do systému";
            }
        }

        $this->view("admin/create-student", ['title' => "Administrace - Nový žák školy",
            'errors' => $errors]);
    }
}