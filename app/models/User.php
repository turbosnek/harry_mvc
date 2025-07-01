<?php

class User extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }
}