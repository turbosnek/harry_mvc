<?php

class HomeController extends Controller {
    public function index() {
        $this->view("home/index", ["title" => "Škola čar a kouzel v bradavicích"]);
    }
}