<?php

class User extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }

    /**
     * Registruje uživatele do systému
     *
     * @param string $first_name - Křestní jméno uživatele
     * @param string $second_name - Příjmení uživatele
     * @param string $email - Email uživatele
     * @param string $password - Heslo uživatele
     * @param string $role - Role uživatele - ROLE_USER výchozí
     *
     * @return bool
     */
    public function createUser(string $first_name, string $second_name, string $email, string $password, string $role): bool {
        $sql = "INSERT INTO user (first_name, second_name, email, password, role)
                VALUES (:first_name, :second_name, :email, :password, :role)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindParam(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);

        $stmt->execute();

        return true;
    }

    /**
     * Zjistí, jestli se v systému nachází email
     *
     * @param string $email - Email uživatele
     *
     * @return bool
     */
    public function checkUserEmail(string $email): bool {
        $sql = "SELECT email FROM user
                WHERE email = :email limit 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Přihlásí uživatele do systému
     *
     * @param string $email - Email uživatele
     * @param string $password - Heslo zživatele
     */
    public function login(string $email, string $password) {
        $sql = "SELECT *
                FROM user
                WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user and password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}