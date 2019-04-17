<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'retrieveCategories':
        $response = retrieveCategories($mysql);
        break;
    case 'retrieveImpacts':
        $response = retrieveImpacts($mysql);
        break;
    case 'retrievePriorities':
        $response = retrievePriorities($mysql);
        break;
    case 'retrieveStatuses':
        $response = retrieveStatuses($mysql);
        break;
    case 'retrieveTasks':
        $response = retrieveTasks($mysql);
        break;
}
echoResponse();
function retrieveCategories(MySQLConnection $mysql) {
    $query = "SELECT * FROM category";
    $categories = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $category = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2]
            ];
            array_push($categories, $category);
        }

    }catch (Exception $e) {
    }
    return $categories;
}
function retrieveImpacts(MySQLConnection $mysql) {
    $query = "SELECT * FROM impact";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2]
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrievePriorities(MySQLConnection $mysql) {
    $query = "SELECT * FROM priority";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2]
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveStatuses(MySQLConnection $mysql) {
    $query = "SELECT * FROM status";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2]
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveTasks(MySQLConnection $mysql) {
    $query = "SELECT 
        t.id AS id, 
        t.name AS name, 
        t.description AS description, 
        s.name AS status, 
        au.name AS assigned_user, 
        ab.name AS assigned_by_user, 
        i.name AS impact, 
        c.name AS category, 
        p.name AS priority, 
        t.comments AS comments, 
        t.time_to_solve AS time_to_solve, 
        t.created_at AS created_at, 
        t.updated_at AS updated_at, 
        t.deleted_at AS deleted_at 
        FROM task t 
        INNER JOIN status s ON s.id = t.status_id 
        INNER JOIN users au ON au.id = t.assigned_user_id 
        INNER JOIN users ab ON ab.id = t.assigned_by_user_id 
        INNER JOIN impact i ON i.id = t.impact_id 
        INNER JOIN category c ON c.id = t.category_id 
        INNER JOIN priority p ON p.id = t.priority_id 
        WHERE t.deleted_at IS NULL";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2],
                'status' => $line[3],
                'assigned_user' => $line[4],
                'assigned_by' => $line[5],
                'impact' => $line[6],
                'category' => $line[7],
                'priority' => $line[8],
                'comments' => $line[9],
                'time_to_solve' => $line[10],
                'created_at' => $line[11],
                'update_at' => $line[12],
                'deleted_at' => $line[12],
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
