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
    public function tryConnectDatabase($try=false) {
        try {
            if($this->connection->select_db($this->database)) {
                $this->isConnectedDataBase = true;
            } else {
                $this->isConnectedDataBase = false;
                if ($try == false) {
                    $this->createDataBaseIfNotExist();
                    $this->tryConnectDatabase(true);
                    $this->createTablesIfNotExist();
                }
            }
        }catch (Exception $e) {
            $this->isConnectedDataBase = false;
            if ($try == false) {
                $this->createDataBaseIfNotExist();
                $this->tryConnectDatabase(true);
            }
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
                var_dump($e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function createTablesIfNotExist() {
        $commands = file_get_contents('commandsSql.sql');
        $this->connection->multi_query($commands);

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
