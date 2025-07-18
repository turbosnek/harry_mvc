<?php

Class HomeController extends Controller
{
    public function index() {
        $this->view("public/home/index", ["title" => "Škola čar a kouzel v bradavicích"]);
    }
}