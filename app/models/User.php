<?php

Class User extends Database {
    public function __construct() {
        $this->conn = $this->connect();
    }

    /**
     * Registruje uživatele do systému
     *
     * @param $first_name - Křestní jméno uživatele
     * @param $second_name - Příjmení uživatele
     * @param $email - Email uživatele
     * @param $password - Heslo uživatele
     * @param $role - Role uživatele. Default ROLE_USER
     *
     * @return bool
     */
    public function register($first_name, $second_name, $email, $password, $role): bool {
        $sql = "INSERT INTO user (first_name, second_name, email, password, role)
                VALUES (:first_name, :second_name, :email, :password, :role)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $stmt->bindValue(":second_name", $second_name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->bindValue(":role", $role, PDO::PARAM_STR);

        $stmt->execute();

        return true;
    }

    /**
     * Zjistí, jestli se v systému nachází zadaný email
     *
     * @param string $email - Email uživatele
     *
     * @return bool
     */
    public function checkEmail(string $email)
    {
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
     * @param string $password - Heslo uživatele
     *
     * @return bool
     */
    public function login(string $email, string $password)
    {
        $sql = "SELECT *
                FROM user
                WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user and password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Získá všchny uživatele z databáze
     *
     * @param array $columns - Výběr určitých sloupečků z tadabáze
     *
     * @return array
     */
    public function getAllUsers(array $columns = ["*"]): array
    {
        $allowed = ['id', 'first_name', 'second_name', 'email', 'role', '*']; // whitelist

        // Odfiltruj jen povolené
        $selected = array_filter($columns, fn($col) => in_array($col, $allowed));

        $sql = "SELECT " . implode(', ', $selected ?: ['*']) . " FROM user";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Získá informace o uživateli podle jeho ID
     *
     * @param int $id - ID uživatele
     * @param array $columns - Výběr sloupečků z databáze
     *
     * @return mixed
     */
    public function getUser(int $id, array $columns = ["*"]): mixed {
        $allowed = ['id', 'first_name', 'second_name', 'email', 'password', 'role', '*'];

        $selected = array_intersect($columns, $allowed);
        $columnString = implode(', ', $selected ?: ['*']);

        $sql = "SELECT $columnString FROM user WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}