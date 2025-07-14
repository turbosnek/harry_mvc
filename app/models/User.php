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
}