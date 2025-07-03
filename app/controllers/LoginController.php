<?php

Class LoginController extends Controller {
    public function index() {
        $this->view("auth/signin", ["title" => "Přihlášení"]);
    }
}