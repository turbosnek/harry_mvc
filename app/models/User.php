<?php

Class User extends Database
{
    public function __construct() {
        $this->conn = $this->connect();
    }

    /**
     * Registruje nového uživatele do systému
     *
     * @param string $first_name - Křestní jméno uživatele
     * @param string $second_name - Příjmení uživatele
     * @param string $email - email uživatele
     * @param string $password - Hewslo uživatele
     * @param string $role - Uživatelská role. Defaultně to přidá ROLE_USER
     *
     * @return bool
     */
    public function register(string $first_name, string $second_name, string $email, string $password, string $role): bool
    {
        $sql = "INSERT INTO user (first_name, second_name, email, password, role)
                VALUES (:first_name, :second_name, :email, :password, :role)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Vytvoření uživatele selhalo");
            }
        } catch (Exception $e) {
            error_log(date('d/m/y H:i') . " " ."Chyba při registraci uživatele: " . $e->getMessage() . "\n", 3, "../../../errors/error.log");
            return false;
        }
    }

    /**
     * Zjistí, jestli se v systému nachází zadaný email
     *
     * @param string $email - Email uživatele
     *
     * @return bool
     */
    public function checkEmail(string $email): bool
    {
        $sql = "SELECT email FROM user
                WHERE email = :email limit 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        try {
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
            } else {
                throw new Exception("Získání emailu selhalo");
            }
        } catch (Exception $e) {
            error_log(date('d/m/y H:i') . " " ."Chyba při získání emailu uživatele: " . $e->getMessage() . "\n", 3, "../../../errors/error.log");
        }
        return false;
    }
}