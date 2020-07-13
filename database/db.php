<?php

class Database {
    protected $conn;
    public function __construct() {
        // echo 'Connecting to DB...' . PHP_EOL;
        $dbName = "TODO-Note";
        $servername = "localhost";
        $username = "root";
        $password = "";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // echo "Connected successfully" . PHP_EOL;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function fetch($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch();
    }

    public function fetchAll($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function fetchColumn($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function query($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    public function insert($tableName, $data) {
        // INSERT INTO `TABLE_NAME` (`col1`, `col2`) VALUES ('val_1', 'val_2');
        $query = "INSERT INTO `$tableName` ";

        $keys = '';
        $values = '';
        $params = [];

        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            $values .= "?,";
            $params[] = $value;
        }
        $keys = trim($keys, ',');
        $values = trim($values, ',');

        $query .= "($keys) VALUES ($values);";

        $this->query($query, $params);

        return $this->conn->lastInsertId();
    }
}
