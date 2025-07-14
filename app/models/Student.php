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

    /**
     * Získá informace o jednom studentovi
     *
     * @param int $id - ID studenta
     * @param string $columns - Sloupce, které chceme získat (např. "id, first_name, age")
     *
     * @return array|null - Pole s daty studenta nebo null, pokud student neexistuje nebo dojde k chybě
     */
    public function getStudent(int $id, string $columns): ?array
    {
        // Povolené sloupce
        $allowedColumns = ['id', 'first_name', 'second_name', 'age', 'life', 'college'];

        // Zpracování požadovaných sloupců
        $columnsArray = array_map('trim', explode(',', $columns));
        $safeColumns = [];

        foreach ($columnsArray as $column) {
            if (in_array($column, $allowedColumns, true)) {
                $safeColumns[] = $column;
            } else {
                // Nepovolený sloupec – můžeš logovat nebo rovnou vyhodit výjimku
                throw new InvalidArgumentException("Nepovolený sloupec: $column");
            }
        }

        // Vytvoření bezpečného seznamu sloupců
        $safeColumnString = implode(', ', $safeColumns);

        $sql = "SELECT $safeColumnString FROM student WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false ? $result : null;
        } catch (PDOException $e) {
            // Logování chyby
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získávání studenta: " . $e->getMessage() . "\n", 3, $logPath);

            return null;
        }
    }

    /**
     * Smaže studenta z databáze podle jeho ID
     *
     * @param int $id - ID studenta
     * @return bool
     */
    public function deleteStudent(int $id): bool {
        $sql = "DELETE FROM student WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0; // Vrací true pouze pokud byl student skutečně smazán
        } catch (Exception $e) {
            // Logování chyby
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při mazání studenta (ID: $id): " . $e->getMessage() . "\n",3, $logPath);
        }

        return false;
    }

    /**
     * Aktualizuje informace o studentovi
     *
     * @param string $first_name - Křestní jmeéno studenta
     * @param string $second_name - Příjmení studenta
     * @param int $age- Věk studenta
     * @param string $life - Informace o studentovi
     * @param string $college - Kolej studenta
     * @param int $id - ID studenta
     *
     * @return bool
     */
    public function updateStudent(string $first_name, string $second_name, int $age, string $life, string $college, int $id): bool
    {
        try {
            // Spuštění transakce
            $this->conn->beginTransaction();

            $sql = "UPDATE student
                SET first_name = :first_name,
                    second_name = :second_name,
                    age = :age,
                    life = :life,
                    college = :college
                WHERE id = :id";

            $stmt = $this->conn->prepare($sql);


            $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
            $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
            $stmt->bindValue(":age", $age, PDO::PARAM_INT);
            $stmt->bindValue(":life", $life, PDO::PARAM_STR);
            $stmt->bindValue(":college", $college, PDO::PARAM_STR);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception("Updatování studenta selhalo.");
            }

            // Potvrzení transakce
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Zpětné vrácení změn při chybě
            $this->conn->rollBack();

            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při updatování studenta: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }
}