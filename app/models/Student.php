<?php

Class Student extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }
}