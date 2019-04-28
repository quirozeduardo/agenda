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
    case 'deleteTask':
        $response = deleteTask($mysql, $dataUsage->_id);
        break;
    case 'deleteUser':
        $response = deleteUser($mysql, $dataUsage->_id);
        break;
    case 'deleteUserType':
        $response = deleteUserType($mysql, $dataUsage->_id);
        break;
    case 'deleteDepartment':
        $response = deleteDepartment($mysql, $dataUsage->_id);
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
function deleteTask(MySQLConnection $mysql, $id) {
    $query = "UPDATE task SET deleted_at=now() WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function deleteUser(MySQLConnection $mysql, $id) {
    $query = "UPDATE users SET deleted_at=now() WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function deleteUserType(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM user_types WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}

function deleteDepartment(MySQLConnection $mysql, $id) {
    $query = "DELETE FROM department WHERE id = $id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}

