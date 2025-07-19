<?php

Class User extends Database
{
    public function __construct()
    {
        $this->conn = $this->connect();
    }

    /**
     * Registruje uživatele do systému
     *
     * @param string $firstName - Křestní jméno uživatele
     * @param string $secondName - Příjmení uživatele
     * @param string $email - Email uživatele
     * @param string $password - Heslo uživatele
     *
     * @return bool - Vrací true při úspěchu, jinak false
     */
    public function register(string $firstName, string $secondName, string $email, string $password): bool
    {
        try {
            // Spuštění transakce
            $this->conn->beginTransaction();

            $sql = "INSERT INTO user (first_name, second_name, email, password, role)
                    VALUES (:first_name, :second_name, :email, :password, :role)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":first_name", $firstName, PDO::PARAM_STR);
            $stmt->bindValue(":second_name", $secondName, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password, PDO::PARAM_STR);
            $stmt->bindValue(":role", 'ROLE_USER', PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Vytvoření uživatele selhalo");
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
     * Zjistí, jestli se v systémnu nachází daný email
     *
     * @param string $email - Email uživatele zadaný při registraci
     *
     * @return bool - true pokud email existuje, jinak false
     */
    public function checkEmail(string $email): bool
    {
        try {
            $sql = "SELECT email FROM user WHERE email = :email LIMIT 1";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValUe(":email", $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
            } else {
                throw new Exception("Dotaz na email selhal");
            }
        } catch (Exception $e) {
            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při kontrolE emailu: " . $e->getMessage() . "\n", 3, $logPath);
            return false;
        }
    }

    /**
     * Přihlásí uživatele do systému
     *
     * @param string $email - Email uživatele
     * @param string $password - Heslo uživatele
     *
     * @return array|false - Vrací základní údaje o uživateli nebo false při neúspěchu
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
                // Odebrat hash hesla z výstupu
                unset($user['password']);
                return $user;
            }
        } catch (Exception $e) {
            // Logování chyby (cesta k souboru je relativní k tomuto souboru)
            $logPath = __DIR__ . "/../../errors/error.log";
            error_log(date('[d/m/y H:i] ') . "Chyba při přihlášení: " . $e->getMessage() . "\n", 3, $logPath);
        }
        return false;
    }
}