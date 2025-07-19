<?php

Class Student extends Database
{
    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /**
     * Přidá nového studenta do systému
     *
     * @param string $firstName - Křestní jméno studenta
     * @param string $secondName - Příjmení studenta
     * @param int $age - Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej studenta
     *
     * @return bool
     */
    public function createStudent(string $firstName, string $secondName, int $age, string $life, string $college): bool
    {
        try {
            // Spuštění transakce
            $this->conn->beginTransaction();

            $sql = "INSERT INTO student (first_name, second_name, age, life, college)
                    VALUES (:first_name, :second_name, :age, :life, :college)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":first_name", $firstName, PDO::PARAM_STR);
            $stmt->bindValue(":second_name", $secondName, PDO::PARAM_STR);
            $stmt->bindValue(":age", $age, PDO::PARAM_INT);
            $stmt->bindValue(":life", $life, PDO::PARAM_STR);
            $stmt->bindValue(":college", $college, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Vytvoření studenta selhalo");
            }

            // Potvrzení transakce
            $this->conn->commit();

            return true;

        } catch (Exception $e) {
            // Zpětné vrácení změn po chybě
            $this->conn->rollBack();

            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při registraci uživatele: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }

    /**
     * Získá všechny studenty z databáze
     *
     * @param array $columns - Pole názvů sloupců, které chceme získat
     *
     * @return array|false - Pole studentů nebo false při chybě
     */
    public function getAllStudents(array $columns = ['*']): array|false
    {
        // Ošetření názvů sloupců (bezpečné, odstraní neznámé znaky). Řešíme v controllweru
        $columnList = implode(', ', $columns);

        $sql = "SELECT $columnList
                FROM student";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získánvání všech studentů: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }

    /**
     * Získá ionformace o jednom studentovi
     *
     * @param int $id - ID Studenta
     * @param array $columns - Sloupce, které chceme získat (např. "id, first_name, second_name)
     *
     * @return array|null - Pole s daty studenta nebo null, pokud student neexistuje nebo dojde k chybě
     */
    public function getStudent(int $id, array $columns = ['*']): array|null
    {
        // Ošetření názvů sloupců (bezpečné, odstraní neznámé znaky). Řešíme v controllweru
        $columnList = implode(', ', $columns);

        $sql = "SELECT $columnList
                FROM student WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false ? $result : null;
        } catch (Exception $e) {
            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získánvání studenta: " . $e->getMessage() . "\n", 3, $logPath);

            return null;
        }
    }

    /**
     * Smaže studenta z databáze podle jeho ID
     *
     * @param int $id - ID studenta
     *
     * @return bool
     */
    public function deleteStudent(int $id): bool
    {
        $sql = "DELETE FROM student WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při mazání studenta: (ID: $id) " . $e->getMessage() . "\n", 3, $logPath);
        }

        return false;
    }

    /**
     * Aktualizuje informace o studentovi
     *
     * @param string $firstname - Křestní jméno studenta
     * @param string $secondName - Příjmení studenta
     * @param int $age - Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej studenta
     * @param int $id - ID studenta
     *
     * @return bool - TRUE, pokud byl záznam aktualizován, jinak FALSE
     */
    public function updateStudent(string $firstname, string $secondName, int $age, string $life, string $college, int $id): bool
    {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE student
                SET first_name = :first_name,
                    second_name = :second_name,
                    age = :age,
                    life = :life,
                    college = :college
                WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":first_name", $firstname, PDO::PARAM_STR);
            $stmt->bindValue(":second_name", $secondName, PDO::PARAM_STR);
            $stmt->bindValue(":age", $age, PDO::PARAM_INT);
            $stmt->bindValue(":life", $life, PDO::PARAM_STR);
            $stmt->bindValue(":college", $college, PDO::PARAM_STR);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $this->conn->commit();

            // Vrátí true, pouze pokud byl alespoň jeden řádek změněn
            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            $this->conn->rollBack();

            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při updatování uživatele: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }
}