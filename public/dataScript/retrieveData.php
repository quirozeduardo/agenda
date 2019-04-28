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
        $response = retrieveTasks($mysql, $dataUsage->filterStatus, $dataUsage->filterPriority, $dataUsage->filterImpact, $dataUsage->filterCategory, $dataUsage->filterDepartment, $dataUsage->user);
        break;
    case 'retrieveUsers':
        $response = retrieveUsers($mysql);
        break;
    case 'retrieveUsersAdmin':
        $response = retrieveUsersAdmin($mysql);
        break;
    case 'retrieveUserTypesAdmin':
        $response = retrieveUserTypesAdmin($mysql);
        break;
    case 'retrieveDepartmentsAdmin':
        $response = retrieveDepartmentsAdmin($mysql);
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
                'description' => $line[2],
                'importance' => $line[3],
                'color' => $line[4],
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
                'description' => $line[2],
                'importance' => $line[3],
                'color' => $line[4],
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
                'description' => $line[2],
                'color' => $line[3],
                'override' => $line[4],
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveTasks(MySQLConnection $mysql, $filterStatus, $filterPriority, $filterImpact, $filterCategory, $department, $user) {


    $query = "SELECT t.*
        FROM task t 
        INNER JOIN user_departments ud ON t.department_id = ud.department_id 
        WHERE t.deleted_at IS NULL
        AND t.status_id ".((intval($filterStatus)==-1)?'!=':'=')." $filterStatus
        AND t.priority_id ".((intval($filterPriority)==-1)?'!=':'=')." $filterPriority
        AND t.impact_id ".((intval($filterImpact)==-1)?'!=':'=')." $filterImpact
        AND t.category_id ".((intval($filterCategory)==-1)?'!=':'=')." $filterCategory";

    if ($user !== null) {
        $query .= " AND ud.user_id = $user->_id";
    }else {
        $query .= " AND ud.user_id = 0";
    }
    if ($department != 0 && $department!= '0') {
        $query .= " AND t.department_id=$department";
    }
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2],
                'status_id' => $line[3],
                'assigned_user_id' => $line[4],
                'assigned_by_id' => $line[5],
                'impact_id' => $line[6],
                'category_id' => $line[7],
                'priority_id' => $line[8],
                'department_id' => $line[9],
                'comments' => $line[10],
                'time_to_solve' => $line[11],
                'created_at' => $line[12],
                'update_at' => $line[13],
                'deleted_at' => $line[14],
            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
        var_dump($e->getMessage());
    }
    return $objects;
}
function retrieveUsers(MySQLConnection $mysql) {
    $query = "SELECT * FROM users WHERE deleted_at is NULL";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'username' => $line[1],
                'name' => $line[2],
                'last_name' => $line[3],
                'email' => $line[4],

            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveUsersAdmin(MySQLConnection $mysql) {
    $query = "SELECT * FROM users";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'username' => $line[1],
                'name' => $line[2],
                'last_name' => $line[3],
                'email' => $line[4],
                'departments' => retrieveDepartmentsByUserId($mysql, $line[0]),
                'userTypes' => retrieveUserTypesByUserId($mysql, $line[0]),
                'created_at' => $line[7],
                'updated_at' => $line[8],
                'deleted_at' => $line[9],

            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveDepartmentsByUserId(MySQLConnection $mysql, $id) {
    $query = "SELECT d.* FROM department d INNER JOIN user_departments ud ON d.id = ud.department_id WHERE ud.user_id = $id";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2],

            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}
function retrieveUserTypesByUserId(MySQLConnection $mysql, $id) {
    $query = "SELECT uts.* FROM user_types uts INNER JOIN user_type ut ON uts.id = ut.type_id WHERE ut.user_id = $id";
    $objects = [];
    try {
        $result = $mysql->query($query);
        while ($line = $result->fetch_row()) {
            $object = [
                'id' => $line[0],
                'name' => $line[1],
                'description' => $line[2],

            ];
            array_push($objects, $object);
        }

    }catch (Exception $e) {
    }
    return $objects;
}

function retrieveUserTypesAdmin(MySQLConnection $mysql) {
    $query = "SELECT * FROM user_types";
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

function retrieveDepartmentsAdmin(MySQLConnection $mysql) {
    $query = "SELECT * FROM department";
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
