<?php
require 'header.php';
require 'jwt_helper.php';
$api_key = '91y83ux12317203471c2';
$dataUsage = $data->data;
switch ($section) {
    case 'login':
        $response = login($mysql, $dataUsage->email, $dataUsage->password);
        break;
    case 'logout':
        $response = deleteRememberToken($mysql, $dataUsage->email);
        break;
    case 'retrieveRememberToken':
        $response = retrieveRememberToken($mysql, $dataUsage->email, $dataUsage->password);
        break;
    case 'verifyRememberToken':
        $response = verifyRememberToken($mysql, $dataUsage->email, $dataUsage->token);
        break;
    case 'register':
        $response = registerNewUser($mysql, $dataUsage->name, $dataUsage->lastName, $dataUsage->userName, $dataUsage->email, $dataUsage->password);
        break;
}
echoResponse();

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
function login(MySQLConnection $mysql, $email, $password) {
    $token = generateRememberTokenIfUserVerified($mysql, $email, $password);
    if ($token != null) {
        $user =  getUserById($mysql, userExist($mysql, $email));
        return [
            "token" => $token,
            "user" => $user,
        ];
    } else {
        return null;
    }
}
function verifyRememberToken(MySQLConnection $mysql, $email, $token) {
    $response = null;
    try {
        $idUser = userExist($mysql, $email);
        if ($idUser > 0) {
            $query = "SELECT id, remember_token FROM users WHERE id=$idUser";
            $result = $mysql->query($query);
            $record = $result->fetch_row();
            $remember_token = strval($record[1]);
            if (trim($remember_token) === trim($token)) {
                $response = getUserById($mysql, $record[0]);
            }
        }

    } catch (Exception $e) {

    }
    return $response;
}
function generateRememberTokenIfUserVerified(MySQLConnection $mysql, $email, $password) {
    global $api_key;
    $token = null;
    try {
        if (verifyUser($mysql, $email, $password)) {
            $date = new DateTime();
            $timestamp = $date->getTimestamp();
            $payload = [
            "email"=> $email,
            "datetime"=> $timestamp
            ];
            $token_JWT = JWT::encode($payload, $api_key);
            $token = storeRememberToken($mysql,$email, $token_JWT);
        }
    } catch (Exception $e) {

    }
    return $token;
}

function retrieveRememberToken(MySQLConnection $mysql, $email, $password) {
    $token = null;
    try {
        if (verifyUser($mysql, $email, $password)) {
            $query = "SELECT remember_token FROM users WHERE email='$email'";
            $result = $mysql->query($query);
            $record = $result->fetch_row();
            $remember_token = $record['remember_token'];
            $token = $remember_token;
        }
    } catch (Exception $e) {

    }
    return $token;
}

function storeRememberToken(MySQLConnection $mysql, $email, $token) {
    $userId = userExist($mysql, $email);
    if ($userId > 0) {
        $query = "UPDATE users SET remember_token= '$token' WHERE id=$userId";
        try {
            $mysql->query($query);
            return $token;
        }catch (Exception $e) {

        }
    }
    return null;
}
function deleteRememberToken(MySQLConnection $mysql, $email) {
    $userId = userExist($mysql, $email);
    if ($userId > 0) {
        $query = "UPDATE users SET remember_token=NULL WHERE id=$userId";
        try {
            $mysql->query($query);
            return true;
        }catch (Exception $e) {

        }
    }
    return false;
}

function verifyUser(MySQLConnection $mysql, $email, $password) {
    $valid = false;
    try {
        if (userExist($mysql, $email) > 0) {
            $query = "SELECT password FROM users WHERE email='$email'";
            $result = $mysql->query($query);
            $password_hash = $result->fetch_row();
            $match = password_verify($password, $password_hash[0]);
            if ($match === true) {
                $valid = true;
            }
        }
    } catch (Exception $e) {

    }
    return $valid;
}
function userExist(MySQLConnection $mysql, $email) {
    $id = 0;
    try {
        $query = "SELECT id FROM users WHERE email='$email'";
        $result = $mysql->query($query);
        if ($result->num_rows > 0) {
            $record = $result->fetch_row();
            $id = intval($record[0]);
        }

    } catch (Exception $e) {

    }
    return $id;
}
function registerNewUser(MySQLConnection $mysql,$name,$lastName,$userName,$email,$password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO 
            users(username, name, last_name, email, password, created_at, updated_at) 
            VALUES ('$userName', '$name', '$lastName', '$email', '$hash', now(), now())";
    try {
        $mysql->query($query);
        return 'success';
    }catch (Exception $e) {
        return 'fail';
    }
}
function getUserById(MySQLConnection $mysql, $id) {
    $query = "SELECT * FROM users WHERE id=$id";
    $object = null;
    try {
        $result = $mysql->query($query);
        if ($result->num_rows > 0) {
            $record = $result->fetch_row();
            $object = [
                'id' => $record[0],
                'username' => $record[1],
                'name' => $record[2],
                'last_name' => $record[3],
                'email' => $record[4],
                'departments' => retrieveDepartmentsByUserId($mysql, $record[0]),
                'userTypes' => retrieveUserTypesByUserId($mysql, $record[0]),
                'created_at' => $record[7],
                'updated_at' => $record[8],
                'deleted_at' => $record[9],
            ];
        }

    }catch (Exception $e) {
    }
    return $object;
}
