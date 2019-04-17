<?php

class MySQLConnection {
    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $database = 'agenda';

    public $connection = null;
    private $isConnected = false;
    private $isConnectedDataBase = false;

    public function __construct()
    {
        $this->connection = new mysqli($this->servername, $this->username, $this->password);
        if ($this->connection->connect_error) {
            $this->isConnected=false;
            return;
        }
        $this->isConnected=true;
        $this->tryConnectDatabase();
    }
    public function tryConnectDatabase() {
        try {
            $this->connection->select_db($this->database);
            $this->isConnectedDataBase = true;
        }catch (Exception $e) {
            $this->isConnectedDataBase = false;
        }
    }
    public function createDataBaseIfNotExist() {
        if ($this->isConnected === true) {
            try {
                if (!$this->connection->select_db($this->database)) {
                    $this->connection->query("CREATE DATABASE $this->database");
                    return true;
                } else {
                    return false;
                }
            }catch(Exception $e) {
                return false;
            }
        }
        return false;
    }
    public function createSchema () {

    }
    public function query($query) {
        $queryResult = $this->connection->query($query);
        if ($queryResult === FALSE) {
            throw new Exception($this->connection->error);
        } else {
            return $queryResult;
        }
    }
    public function closeConnection () {
        if ($this->isConnected) {
            $this->connection->close();
        }
    }
}
