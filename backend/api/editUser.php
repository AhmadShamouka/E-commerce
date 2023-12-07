<?php
header('Access-Control-Allow-Origin: *');
include("../connection.php");
require __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['username']) && isset($data['role_name'])) {
    $userid = $data['id'];
    $username = $data['username'];
    $role_name = $data['role_name'];
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

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $query = $mysqli->prepare('SELECT * FROM users WHERE userid = ?');
                $query->bind_param('i', $userid);
                $query->execute();
                $result = $query->get_result();

                if ($result->num_rows > 0) {
                    $userData = $result->fetch_assoc();
                    echo json_encode($userData);
                } else {
                    echo json_encode(['error' => 'user not found']);
                }

                $query->close();
                break;

            case 'PUT':
                $json_data = file_get_contents("php://input");
                $data = json_decode($json_data, true);

                $query = $mysqli->prepare('UPDATE users SET username=?, role_name=? WHERE userid = ?');
                $query->bind_param("ssi", $data['username'], $data['role_name'], $userid);

                if ($query->execute()) {
                    echo json_encode(['message' => 'user data updated successfully']);
                } else {
                    echo json_encode(['error' => 'Error updating user data']);
                }

                $query->close();
                break;

            case 'DELETE':
                $json_data = file_get_contents("php://input");
                $data = json_decode($json_data, true);

                $query = $mysqli->prepare('DELETE FROM users WHERE userid = ?');
                $query->bind_param('i', $userid);
                $result = $query->execute();

                if ($result) {
                    echo json_encode(['message' => 'user data deleted successfully']);
                } else {
                    echo json_encode(['error' => 'Error deleting user data']);
                }

                $query->close();
                break;

            default:
                echo json_encode(['error' => 'Unsupported request method']);
                break;
        }
    } catch (ExpiredException $e) {
        http_response_code(401);
        echo json_encode(["error" => "expired"]);
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid token"]);
    }
} else {
    echo json_encode(["error" => "Invalid token"]);
}
?>