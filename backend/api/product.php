<?php

include("../connection.php");
require __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
$data = json_decode(file_get_contents("php://input"), true);



$data = json_decode(file_get_contents("php://input"), true);
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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($data['id'])) {
        $userid = $data['id'];
        $checkUserRoleQuery = $mysqli->prepare("SELECT role_name FROM users WHERE userid = ?");
        $checkUserRoleQuery->bind_param("i", $userid);
        $checkUserRoleQuery->execute();
        $checkUserRoleQuery->bind_result($userRole);
        $checkUserRoleQuery->fetch();
        $checkUserRoleQuery->close();

        if ($userRole == 'Seller') {
            $query = $mysqli->prepare("SELECT * FROM products WHERE user_id = ?");
            $query->bind_param("i", $userid);
            
            if ($query->execute()) {
                $result = $query->get_result();
                $products = [];
                
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                } 
                echo json_encode(["success" => true, "products" => $products]);
            } else {
                echo json_encode(["success" => false, "error" => "Error executing query"]);
            }
            $query->close();
        } else {
            echo json_encode(["success" => false, "error" => "User does not have the required role or doesn't exist"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid data format"]);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $userid = $data['id'];
        $checkUserRoleQuery = $mysqli->prepare("SELECT role_name FROM users WHERE userid = ?");
        $checkUserRoleQuery->bind_param("i", $userid);

        $checkUserRoleQuery->execute();
        $checkUserRoleQuery->bind_result($userRole);
        $checkUserRoleQuery->fetch();
        $checkUserRoleQuery->close();

        if ($userRole == 'Seller') {
            if (isset($data['productid'])) {
                $productid = $data['productid'];
                $deleteQuery = $mysqli->prepare("DELETE FROM products WHERE productid = ? AND user_id = ?");
                $deleteQuery->bind_param("ii", $productid, $userid);

                if ($deleteQuery->execute()) {
                    echo json_encode(["success" => true, "message" => "Product deleted successfully"]);
                } else {
                    echo json_encode(["success" => false, "error" => "Error executing delete query"]);
                }

                $deleteQuery->close();
            } else {
                echo json_encode(["success" => false, "error" => "Product ID is required for deletion"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "User does not have the required role or doesn't exist"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid data format"]);
    }
}
 elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($data['productname']) && isset($data['description']) && isset($data['price']) && isset($data['id'])) {
        $productname = $data['productname'];
        $description = $data['description'];
        $price = $data['price'];
        $userid = $data['id'];
        $productid = $data['id'];
        $checkUserRoleQuery = $mysqli->prepare("SELECT role_name FROM users WHERE userid = ?");
        $checkUserRoleQuery->bind_param("i", $userid);

        $checkUserRoleQuery->execute();
        $checkUserRoleQuery->bind_result($userRole);
        $checkUserRoleQuery->fetch();
        $checkUserRoleQuery->close();

        if ($userRole == 'Seller') {
            $query = $mysqli->prepare("INSERT INTO products (productname, description, price, userid) 
                           SELECT ?, ?, ?, userid FROM users WHERE userid = ?");
            $query->bind_param("ssii", $productname, $description, $price, $userid);
            if ($query->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error executing query"]);
            }

            $query->close();
        } else {
            echo json_encode(["success" => false, "error" => "User does not have the required role or doesn't exist"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid data format"]);
    }

} else {

    http_response_code(405); 
    echo json_encode(["success" => false, "error" => "Unsupported HTTP method"]);
}
}
catch (ExpiredException $e) {
    http_response_code(401);
    echo json_encode(["error" => "expired"]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
}
?>