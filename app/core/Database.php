<?php


class Database {
    private $host = "localhost";
    private $db_name = "school_mvc";
    private $username = "root";
    private $password = "";
    protected $conn;

    public function connect() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Připojení k databázi selhalo: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}