<?php


Class Student extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }

    /**
     * Vloží studenta do databáse systému
     *
     * @param string $first_name - Křestní jméno studenta
     * @param string $second_name - Příjmení studenta
     * @param int $age - Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej studenta
     *
     * @return bool
     */
    public function create(string $first_name, string $second_name, int $age, string $life, string $college): bool {
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

    /**
     * Získá všechny studenty z databáze
     *
     * @param string $columns - Vybrané sloupečky, které potřebujeme
     *
     * @return array
     */
    public function getAllStudents(string $columns = "*"): array {
        $sql = "SELECT $columns
                FROM student";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Získá data o jednom studentovi
     *
     * @param int $id - ID studenta
     * @param string $columns - Sloupečky z databáze, se kterými budeme pracovat
     *
     * @return mixed
     */
    public function getStudent(int $id, string $columns = "*"): mixed {
        $sql = "SELECT $columns
                FROM student
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Smaže studenta ze systému
     *
     * @param int $id - ID Studenta
     *
     * @return bool
     */
    public function deleteStudent(int $id): bool {
        $sql = "DELETE FROM student
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return true;
    }
}