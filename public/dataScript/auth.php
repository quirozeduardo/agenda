<?php
require 'header.php';
require 'jwt_helper.php';
$api_key = '91y83ux12317203471c2';
$dataUsage = $data->data;
switch ($section) {
    case 'login':
        $response = generateRememberTokenIfUserVerified($mysql, $dataUsage->email, $dataUsage->password);
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
function verifyRememberToken(MySQLConnection $mysql, $email, $token) {
    $match = false;
    try {
        $idUser = userExist($mysql, $email);
        if ($idUser > 0) {
            $query = "SELECT remember_token FROM users WHERE id=$idUser";
            $result = $mysql->query($query);
            $record = $result->fetch_row();
            $remember_token = strval($record[0]);
            if (trim($remember_token) === trim($token)) {
                $match = true;
            }
        }

    } catch (Exception $e) {

    }
    return $match;
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
