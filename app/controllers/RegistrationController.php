<?php

class RegistrationController extends Controller {
    public function index() {
        $this->view("registration/index", ["title" => "Registrace"]);
    }
}