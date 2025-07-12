<?php


class Database {
    private $host = "localhost";
    private $db_name = "school_mvc";
    private $username = "root";
    private $password = "";
    protected $conn;

    public function connect() {
        if ($this->conn === null) { // ✅ Only create connection if not already established
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Připojení k databázi selhalo: " . $e->getMessage()); // 🔴 Stop script if connection fails
            }
        }
        return $this->conn; // ✅ Always return the connection
    }
}