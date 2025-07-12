<?php


class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'harry_mvc';
    protected $dbh;

    public function __construct() {
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->user, $this->pass);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}