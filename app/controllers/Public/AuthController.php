<?php

require_once './app/helpers/CsrfHelper.php';

Class AuthController extends Controller
{
    public function login():void
    {
        $csrfToken = CsrfHelper::generateToken();

        $this->view("public/auth/login", ['title' => "Přihlášení",
//            'errors' => $errors,
            'csrfToken' => $csrfToken]);
    }
}