<?php
require 'DB.php';
$mysql = new MySQLConnection();
$data = json_decode(file_get_contents("php://input"));
$response = '';
$section = '';
if (isset($data->action)) {
    $action = $data->action;
    if (isset($data->section)) {
        $section = $data->section;
    }
}

function echoResponse() {
    global $response;
    echo json_encode(['response' => $response]);;
}
