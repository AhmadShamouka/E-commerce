<?php
header('Access-Controll-Allow-Origin:*');
include('../connection.php');

$username = $_POST['username'];
$password = $_POST['password'];
$role_name = $_POST['role_name'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = $mysqli->prepare('insert into users(username,password,role_name) 
values(?,?,?)');
$query->bind_param('sss', $username, $hashed_password,$role_name);
$query->execute();

$response = [];
$response["status"] = "true";

echo json_encode($response);