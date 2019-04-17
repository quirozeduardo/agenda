<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'storeCategory':
        $response = storeCategory($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storeImpact':
        $response = storeImpact($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storePriority':
        $response = storePriority($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storeStatus':
        $response = storeStatus($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
}
echoResponse();
function storeCategory(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            category(name, description, created_at, updated_at) 
            VALUES ('$name', '$description', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storeImpact(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            impact(name, description, created_at, updated_at) 
            VALUES ('$name', '$description', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storePriority(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            priority(name, description, created_at, updated_at) 
            VALUES ('$name', '$description', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storeStatus(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            status(name, description, created_at, updated_at) 
            VALUES ('$name', '$description', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}

