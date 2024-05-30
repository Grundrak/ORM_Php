<?php

$config = require './config.php';
require './model.php';
class Database
{
    private $db;

    private $stmt;
    public function __construct()
    {
        global $config;
        $this->db = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }
    public function createTable($class) {
        $reflectionClass = new ReflectionClass($class);
        $tableAnnotation = $reflectionClass->getAttributes(Table::class)[0]->newInstance();
        $tableName = $tableAnnotation->name;
        $columns = [];
    
        foreach ($reflectionClass->getProperties() as $property) {
            $columnAnnotation = $property->getAttributes(Column::class)[0]->newInstance();
            $columnString = $columnAnnotation->name . ' ' . $columnAnnotation->type;
            if ($columnAnnotation->primary) {
                $columnString .= ' PRIMARY KEY';
                if ($columnAnnotation->autoIncrement) {
                    $columnString .= ' AUTO_INCREMENT';
                }
            }
            if (!$columnAnnotation->nullable) {
                $columnString .= ' NOT NULL';
            }
            $columns[] = $columnString;
        }
    
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (" . implode(', ', $columns) . ");";
        $this->executeQuery($sql);
    }
    public function executeQuery($sql)
    {
        if ($this->db->query($sql) === true) {
            return true;
        } else {
            throw new Exception("Error executing query: " . $this->db->error);
        }
    }

    public function getResult($sql)
    {
        $result = $this->db->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception("Error getting result: " . $this->db->error);
        }
    }

    public function prepareStatement($sql)
    {
        $this->stmt = $this->db->prepare($sql);
        if (!$this->stmt) {
            throw new Exception("Error preparing statement: " . $this->db->error);
        }
        return $this->stmt;
    }

    public function bindParams($types, ...$params)
    {
        if (!$this->stmt->bind_param($types, ...$params)) {
            throw new Exception("Error binding parameters: " . $this->stmt->error);
        }
    }

    public function executeStatement()
    {
        if (!$this->stmt->execute()) {
            throw new Exception("Error executing statement: " . $this->stmt->error);
        }
        return $this->stmt;
    }

    public function fetchData()
    {
        $result = $this->stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception("Error fetching data: " . $this->stmt->error);
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
