<?php

Class User extends Database
{
    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /**
     * Registruje nového uživatele do systému
     *
     * @param string $first_name - Křestní jméno uživatele
     * @param string $second_name - Příjmení uživatele
     * @param string $email - Email uživatele
     * @param string $password - Heslo uživatele (již hashované)
     * @param string $role - Uživatelská role, např. ROLE_USER
     *
     * @return bool
     */
    public function register(string $first_name, string $second_name, string $email, string $password, string $role): bool
    {
        try {
            // Spuštění transakce
            $this->conn->beginTransaction();

            $sql = "INSERT INTO user (first_name, second_name, email, password, role)
                    VALUES (:first_name, :second_name, :email, :password, :role)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
            $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);
            $stmt->bindValue(":role", $role, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Vytvoření uživatele selhalo.");
            }

            // Potvrzení transakce
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Zpětné vrácení změn při chybě
            $this->conn->rollBack();

            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při registraci uživatele: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }

    /**
     * Zjistí, jestli se v systému nachází zadaný email
     *
     * @param string $email - Email uživatele
     *
     * @return bool - Vrací true, pokud email existuje
     */
    public function checkEmail(string $email): bool
    {
        $sql = "SELECT email FROM user WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        try {
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
            } else {
                throw new Exception("Dotaz na email selhal.");
            }
        } catch (Exception $e) {
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při kontrole emailu: " . $e->getMessage() . "\n", 3, $logPath);
            return false;
        }
    }

    /**
     * Přihlášení uživatele do systému
     *
     * @param string $email - Email uživatele
     * @param string $password - Heslo uživatele
     *
     * @return array|false
     */
    public function login(string $email, string $password): array|false
    {
        $sql = "SELECT *
                FROM user
                WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        try {
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user and password_verify($password, $user['password'])) {
                return $user;
            }


        } catch (Exception $e) {
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při přihlášení: " . $e->getMessage() . "\n", 3, $logPath);
        }
        return false;
    }

    /**
     * Získá všechny uživatele z databáze
     *
     * @param array $columns - Pole názvů sloupců, které chceme získat
     *
     * @return array|false - Pole uživatelů nebo false při chybě
     */
    public function getAllUsers(array $columns = ['id, first_name, second_name, email']): array|false
    {
        // Povolené sloupce – whitelist
        $allowedColumns = ['id', 'first_name', 'second_name, email'];

        // Filtrace vstupního pole podle whitelistu
        $filteredColumns = array_intersect($columns, $allowedColumns);

        // Pokud je výsledek prázdný, zamezíme SELECT bez sloupců
        if (empty($filteredColumns)) {
            return false;
        }

        // Sestavení dotazu
        $columnList = implode(", ", $filteredColumns);
        $sql = "SELECT $columnList FROM user";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Logování chyby
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získávání uživatelů: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }

    /**
     * Získá jednoho uživatele z databáze
     *
     * @param int $id - ID uživatele
     * @param array $columns - Pole názvů sloupců, které chceme získat
     *
     * @return array|null|false - Data uživatele, null pokud neexistuje, false při chybě
     */
    public function getUser(int $id, array $columns = ['id', 'first_name', 'second_name', 'email', 'password', 'role']): array|null|false
    {
        $allowedColumns = ['id', 'first_name', 'second_name', 'email', 'password', 'role'];

        $filteredColumns = array_intersect($columns, $allowedColumns);

        if (empty($filteredColumns)) {
            return false;
        }

        $columnList = implode(", ", $filteredColumns);
        $sql = "SELECT $columnList FROM user WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (Exception $e) {
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při získávání uživatele s ID $id: " . $e->getMessage() . "\n", 3, $logPath);

            return false;
        }
    }
}