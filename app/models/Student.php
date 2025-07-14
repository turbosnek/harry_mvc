<?php

Class Student extends Database
{
    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /**
     * Vytvoří nového studenta a přidá do databáze
     *
     * @param string $first_name - Křestní jméno studenta
     * @param string $second_name - Příjmení studenta
     * @param int $age - Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej Studenta
     *
     * @return bool
     */
    public function create(string $first_name, string $second_name, int $age, string $life, string $college): bool
    {
        try {
            // Spuštění transakce
            $this->conn->beginTransaction();

            $sql = "INSERT INTO student (first_name, second_name, age, life, college)
                VALUES (:first_name, :second_name, :age, :life, :college)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
            $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
            $stmt->bindValue(":age", $age, PDO::PARAM_INT);
            $stmt->bindValue(":life", $life, PDO::PARAM_STR);
            $stmt->bindValue(":college", $college, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Vytvoření studenta selhalo.");
            }

            // Potvrzení transakce
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Zpětné vrácení změn při chybě
            $this->conn->rollBack();

            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při vytváření studenta: " . $e->getMessage() . "\n", 3, $logPath);

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
    public function getAllStudents(array $columns = ['id', 'first_name', 'second_name']): array|false
    {
        // Povolené sloupce – whitelist
        $allowedColumns = ['id', 'first_name', 'second_name'];

        // Filtrace vstupního pole podle whitelistu
        $filteredColumns = array_intersect($columns, $allowedColumns);

        // Pokud je výsledek prázdný, zamezíme SELECT bez sloupců
        if (empty($filteredColumns)) {
            return false;
        }

        // Sestavení dotazu
        $columnList = implode(", ", $filteredColumns);
        $sql = "SELECT $columnList FROM student";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Logování chyby
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získávání studentů: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }
}