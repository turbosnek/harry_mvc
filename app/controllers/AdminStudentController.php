<?php

class AdminStudentController extends Controller
{
    private Student $studentModel;

    public function __construct() {
        $this->studentModel = new Student();
    }

    public function allStudents() {
        $errors = [];

        if (empty($errors)) {
            $students = $this->studentModel->getAllStudent("id, first_name, second_name");
        } else {
            $errors[] = "Žádní žáci nebyly nalezeni";
        }

        $this->view("admin/students/index", ["title" => "Administrace - Seznam žáků",
                                                        'errors' => $errors,
                                                        'students' => $students]);
    }
}