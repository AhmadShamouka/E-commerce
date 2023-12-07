<?php
header('Access-Controll-Allow-Origin:*');
include("../connection.php");
require __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['headers'])) {
    $headers = $data['headers'];
    $id= $data['id'];
$headers = getallheaders();
if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit();
}

$authorizationHeader = $headers['Authorization'];
$token = null;

$token = trim(str_replace("Bearer", '', $authorizationHeader));
if (!$token) {
    http_response_code(401);
    echo json_encode(["error" => "unauthorized"]);
    exit();
}
try {
    $key = "your_secret";
    $decoded = JWT::decode($token, new Key($key, 'HS256'));

        $query = $mysqli->prepare('select* from users where userid=?');
        $query->bind_param('i', $id);
        $query->execute();
        $array = $query->get_result();
        $response = [];
        $user = $array->fetch_assoc();
        $response = $user;
        
    echo json_encode($response);
} catch (ExpiredException $e) {
    http_response_code(401);
    echo json_encode(["error" => "expired"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
}
}