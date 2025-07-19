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
    public function getAllStudents(array $columns =['*']): array|false
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
}