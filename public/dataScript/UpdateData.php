<?php
require 'header.php';
$dataUsage = $data->data;
switch ($section) {
    case 'updateCategory':
        $response = updateCategory($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'updateDepartment':
        $response = updateDepartment($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
        break;
    case 'updateUserType':
        $response = updateUserType($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_description);
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
    case 'updateTask':
        $response = updateTask($mysql,
            $dataUsage->_id,
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
    case 'updateTaskStatus':
        $response = updateTaskStatus($mysql, $dataUsage->_id, $dataUsage->_status->_id);
        break;
    case 'updateUser':
        $response = updateUser($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_email, $dataUsage->_lastName, $dataUsage->_userName);
        break;
    case 'updateUserAdmin':
        $response = updateUserAdmin($mysql, $dataUsage->_id, $dataUsage->_name, $dataUsage->_email, $dataUsage->_lastName, $dataUsage->_departments, $dataUsage->_userTypes, $dataUsage->_userName, $dataUsage->_deleted_at, $dataUsage->_password);
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
function updateDepartment(MySQLConnection $mysql, $id, $name, $description) {
    $query = "UPDATE department SET name='$name', description='$description', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updateUserType(MySQLConnection $mysql, $id, $name, $description) {
    $query = "UPDATE user_types SET name='$name', description='$description', updated_at=now() WHERE id=$id";
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
function updateTaskStatus(MySQLConnection $mysql, $id, $idStatus) {
    $query = "UPDATE task SET status_id=$idStatus, updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}

function updateUser(MySQLConnection $mysql, $id, $name, $email, $lastName, $username) {
    $query = "UPDATE users SET name='$name', email='$email', last_name='$lastName', username='$username', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        return false;
    }
}
function updateUserAdmin(MySQLConnection $mysql, $id, $name, $email, $lastName, $departments, $userTypes,  $username, $deleted, $password) {
    if (intval(userNameExist($mysql, $username, $id))>0) {
        return 'username_exist';
    }
    if(intval(userExist($mysql, $email, $id))>0) {
        return 'email_exist';
    }
    $deleted = ($deleted==null)?'NULL': $deleted;
    $query = "UPDATE users SET name='$name', email='$email', last_name='$lastName', username='$username', deleted_at=$deleted, updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        if ($password !== null) {
            if (strlen($password)>5) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE users SET password='$hash', updated_at=now() WHERE id=$id";
                $mysql->query($query);
            }
        }
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
        var_dump($e->getMessage());
        return 'error';
    }
}
function updateTask(MySQLConnection $mysql,
                    $id,
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

   $query = "UPDATE task SET name='$name', description='$description', status_id=$status, assigned_user_id=$assignedUser, assigned_by_user_id=$assignedByUser, impact_id=$impact, category_id=$category, priority_id=$priority, department_id=$department, comments='$comments', updated_at=now() WHERE id=$id";
    try {
        $mysql->query($query);
        return true;
    }catch (Exception $e) {
        var_dump($e->getMessage());
        return false;
    }    

}

function userExist(MySQLConnection $mysql, $email, $id) {
    $idRt = 0;
    try {
        $query = "SELECT id FROM users WHERE BINARY email='$email' AND id != $id";
        $result = $mysql->query($query);
        if ($result->num_rows > 0) {
            $record = $result->fetch_row();
            $idRt = intval($record[0]);
        }

    } catch (Exception $e) {

    }
    return $idRt;
}
function userNameExist(MySQLConnection $mysql, $userName, $id) {
    $idRt = 0;
    try {
        $query = "SELECT id FROM users WHERE BINARY username='$userName' AND id != $id";
        $result = $mysql->query($query);
        if ($result->num_rows > 0) {
            $record = $result->fetch_row();
            $idRt = intval($record[0]);
        }

    } catch (Exception $e) {

    }
    return $idRt;
}