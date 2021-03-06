<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'storeCategory':
        $response = storeCategory($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storeUserType':
        $response = storeUserType($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storeDepartment':
        $response = storeDepartment($mysql, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'storeImpact':
        $response = storeImpact($mysql, $dataUsage->_name, $dataUsage->_description, $dataUsage->_importance, $dataUsage->_color);
        break;
    case 'storePriority':
        $response = storePriority($mysql, $dataUsage->_name, $dataUsage->_description, $dataUsage->_importance, $dataUsage->_color);
        break;
    case 'storeStatus':
        $response = storeStatus($mysql, $dataUsage->_name, $dataUsage->_description, $dataUsage->_override, $dataUsage->_color);
        break;
    case 'storeTask':
        $response = storeTask($mysql,
            $dataUsage->_name,
            $dataUsage->_description,
            $dataUsage->_status->_id,
            $dataUsage->_assignedUser->_id,
            $dataUsage->_assignedByUser->_id,
            $dataUsage->_impact->_id,
            $dataUsage->_category->_id,
            $dataUsage->_priority->_id,
            $dataUsage->_department->_id,
            $dataUsage->_comments);
        break;
    case 'storeUserAdmin':
        $response = storeUserAdmin($mysql, $dataUsage->_name,$dataUsage->_lastName,trim($dataUsage->_userName),trim($dataUsage->_email),$dataUsage->_password, $dataUsage->_departments, $dataUsage->_userTypes);
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
function storeUserType(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            user_types(name, description, created_at, updated_at) 
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
function storeDepartment(MySQLConnection $mysql, $name, $description) {
    $query = "INSERT INTO 
            department(name, description, created_at, updated_at) 
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
function storeImpact(MySQLConnection $mysql, $name, $description, $importance, $color) {
    $query = "INSERT INTO 
            impact(name, description, importance, color, created_at, updated_at) 
            VALUES ('$name', '$description', $importance, '$color', now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description,
            'importance' => $importance,
            'color' => $color
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}
function storePriority(MySQLConnection $mysql, $name, $description, $importance, $color) {
    $query = "INSERT INTO 
            priority(name, description, importance, color, created_at, updated_at) 
            VALUES ('$name', '$description', $importance, '$color',  now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description,
            'importance' => $importance,
            'color' => $color
        ];
        return $object;
    }catch (Exception $e) {
        var_dump($e->getMessage());
        return null;
    }
}
function storeStatus(MySQLConnection $mysql, $name, $description, $override, $color) {
    $query = "INSERT INTO 
            status(name, description, override, color, created_at, updated_at) 
            VALUES ('$name', '$description', $override, '$color',  now(), now())";
    try {
        $mysql->query($query);
        $object = [
            'id' => $mysql->connection->insert_id,
            'name' => $name,
            'description' => $description,
            'override' => $override,
            'color' => $color
        ];
        return $object;
    }catch (Exception $e) {
        return null;
    }
}

function storeTask(MySQLConnection $mysql,
            $name,
            $description,
            $status,
            $assignedUser,
            $assignedByUser,
            $impact,
            $category,
            $priority,
            $department,
            $comments) {
    $query = "INSERT INTO 
            task(name, description, status_id, assigned_user_id, assigned_by_user_id, impact_id, category_id, priority_id, department_id, comments, time_to_solve, created_at, updated_at) 
            VALUES ('$name', '$description', $status, $assignedUser, $assignedByUser, $impact, $category, $priority, $department, '$comments', 0, now(), now())";
    $object = null;
    try {
        $mysql->query($query);
        $lastId =  $mysql->connection->insert_id;
        $query = "SELECT t.*
        FROM task t 
        WHERE t.deleted_at IS NULL
        AND t.id = $lastId";
        try {
            $result = $mysql->query($query);
            if ($result->num_rows > 0) {
                $line = $result->fetch_row();
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
                return $object;
            }

        }catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }catch (Exception $e) {
        var_dump($e->getMessage());
    }
    return $object;
}

function storeUserAdmin(MySQLConnection $mysql,$name,$lastName,$userName,$email,$password, $departments, $userTypes){
    if (userNameExist($mysql, $userName)>0) {
        return 'username_exist';
    }
    if(userExist($mysql, $email)>0) {
        return 'email_exist';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO 
            users(username, name, last_name, email, password, created_at, updated_at) 
            VALUES ('$userName', '$name', '$lastName', '$email', '$hash', now(), now())";
    try {
        $mysql->query($query);
        $id =  $mysql->connection->insert_id;
        $mysql->query("DELETE FROM user_departments WHERE user_id = $id");
        foreach ($departments as $department) {
            $mysql->query("INSERT INTO user_departments(user_id, department_id) VALUES ($id, $department->_id)");
        }
        $mysql->query("DELETE FROM user_type WHERE user_id = $id");
        foreach ($userTypes as $userType) {
            $mysql->query("INSERT INTO user_type(user_id, type_id) VALUES ($id, $userType->_id)");
        }
        return 'success';
    }catch (Exception $e) {
        return 'fail';
    }
}

function userExist(MySQLConnection $mysql, $email, $id=0) {
    $id = 0;
    try {
        $query = "SELECT id FROM users WHERE BINARY email='$email'";
        $result = $mysql->query($query);
        if ($result->num_rows > 0) {
            $record = $result->fetch_row();
            $id = intval($record[0]);
        }

    } catch (Exception $e) {

    }
    return $id;
}
function userNameExist(MySQLConnection $mysql, $userName) {
    $id = 0;
    try {
        $query = "SELECT id FROM users WHERE BINARY username='$userName'";
        $result = $mysql->query($query);
        if ($result->num_rows > 0) {
            $record = $result->fetch_row();
            $id = intval($record[0]);
        }

    } catch (Exception $e) {

    }
    return $id;
}