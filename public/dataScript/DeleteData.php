<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'deleteCategory':
        $response = deleteCategory($mysql, $dataUsage->_id);
        break;
    case 'deleteImpact':
        $response = deleteImpact($mysql, $dataUsage->_id);
        break;
    case 'deletePriority':
        $response = deletePriority($mysql, $dataUsage->_id);
        break;
    case 'deleteStatus':
        $response = deleteStatus($mysql, $dataUsage->_id);
        break;
}
echoResponse();
function deleteCategory(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM category WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function deleteImpact(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM impact WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function deletePriority(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM priority WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function deleteStatus(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM status WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
