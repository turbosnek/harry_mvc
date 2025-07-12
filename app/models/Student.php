<?php

Class Student extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }

    /**
     * Přidá nového studenta do systému
     *
     * @param string $first_name
     * @param string $second_name
     * @param int $age
     * @param string $life
     * @param string $college
     *
     * @return bool
     */
    public function createStudent(string $first_name, string $second_name, int $age, string $life, string $college): bool
    {
        $sql = "INSERT INTO student (first_name, second_name, age, life, college)
                VALUES (:first_name, :second_name, :age, :life, :college)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindValue(":age", $age, PDO::PARAM_INT);
        $stmt->bindValue(":life", $life, PDO::PARAM_STR);
        $stmt->bindValue(":college", $college, PDO::PARAM_STR);

        $stmt->execute();

        return true;
    }
}