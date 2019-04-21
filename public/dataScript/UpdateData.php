<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'updateCategory':
        $response = updateCategory($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'updateImpact':
        $response = updateImpact($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description, $dataUsage->_importance, $dataUsage->_color);
        break;
    case 'updatePriority':
        $response = updatePriority($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description, $dataUsage->_importance, $dataUsage->_color);
        break;
    case 'updateStatus':
        $response = updateStatus($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description, $dataUsage->_override, $dataUsage->_color);
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
function updateImpact(MySQLConnection $mysql, $id, $name, $description, $importance, $color) {
    $query = "UPDATE impact SET name='$name', description='$description', importance=$importance, color='$color', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updatePriority(MySQLConnection $mysql, $id, $name, $description, $importance, $color) {
    $query = "UPDATE priority SET name='$name', description='$description', importance=$importance, color='$color', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updateStatus(MySQLConnection $mysql, $id, $name, $description, $override, $color) {
    $query = "UPDATE status SET name='$name', description='$description', override=$override, color='$color', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
