<?php

Class Student extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }

    public function getAllStudent($columns = "*") {
        $sql = "SELECT $columns
                FROM student";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}