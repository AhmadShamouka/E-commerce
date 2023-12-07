<?php
header('Access-Controll-Allow-Origin:*');
include('../connection.php');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['username']) && isset($data['password']) && isset($data['role_name'])) {
    $username = $data['username'];
    $password = $data['password'];
    $role_name = $data['role_name'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $mysqli->prepare('insert into users(username,password,role_name) 
values(?,?,?)');
$query->bind_param('sss', $username, $hashed_password,$role_name);
$query->execute();

$response = [];
$response["status"] = "true";

echo json_encode($response);
}