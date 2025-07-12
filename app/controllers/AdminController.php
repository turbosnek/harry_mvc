<?php

class AdminController extends Controller {
    public function index() {
        $this->view("admin/home/index", ["title" => "Administrace"]);
    }
}