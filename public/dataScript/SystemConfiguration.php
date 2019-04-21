<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'storeValue':
        $response = storeValue($mysql, $dataUsage->_key, $dataUsage->_value);
        break;
    case 'retrieveValue':
        $response = retrieveValue($mysql, $dataUsage->_key);
        break;
    case 'updateValue':
        $response = updateValue($mysql,$dataUsage->_id, $dataUsage->_key, $dataUsage->_value);
        break;
    case 'deleteValue':
        $response = deleteValue($mysql, $dataUsage->_id);
        break;
    case 'retrieveConfigurations':
        $response = retrieveConfigurations($mysql);
        break;
}
echoResponse();
function storeValue(MySQLConnection $mysql, $key, $value) {
    $query = "INSERT INTO 
            system_configuration(`key`, `value`, created_at, updated_at) 
            VALUES ('$key', '$value', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'key' => $key,
            'value' => $value
        ];
        return $object;
    }catch (Exception $e) {
        var_dump($e->getMessage());
        return null;
    }
}
function deleteValue(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM system_configuration WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updateValue(MySQLConnection $mysql, $id, $key, $value) {
    $query = "UPDATE system_configuration SET `key`='$key', `value`='$value', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function retrieveValue(MySQLConnection $mysql, $key) {
    $query = "SELECT * FROM system_configuration WHERE `key`='$key'";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'key' => $line[1],
                'value' => $line[2]
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveConfigurations(MySQLConnection $mysql) {
    $query = "SELECT * FROM system_configuration";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'key' => $line[1],
                'value' => $line[2],
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
