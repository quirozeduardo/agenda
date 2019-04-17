<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'updateCategory':
        $response = updateCategory($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'updateImpact':
        $response = updateImpact($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'updatePriority':
        $response = updatePriority($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'updateStatus':
        $response = updateStatus($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
}
echoResponse();
function updateCategory(MySQLConnection $mysql, $id, $name, $description) {
    $query = "UPDATE category SET name='$name', description='$description', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updateImpact(MySQLConnection $mysql, $id, $name, $description) {
    $query = "UPDATE impact SET name='$name', description='$description', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updatePriority(MySQLConnection $mysql, $id, $name, $description) {
    $query = "UPDATE priority SET name='$name', description='$description', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updateStatus(MySQLConnection $mysql, $id, $name, $description) {
    $query = "UPDATE status SET name='$name', description='$description', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
