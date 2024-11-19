<?php
// Include database configuration
include '../db/config.php';
session_start();
// echo "This is the usrs view";

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$role = $_SESSION['role'];
// $role = 1;

// Main endpoint to get dashboard content based on role
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($role === 1) {
        echo json_encode(get_all_users($conn));
    } 
    else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
}


function get_all_users($conn) {
    $query = "SELECT CONCAT(fname, ' ', lname) as fullname, email, role, created_at FROM users";
    $result = $conn->query($query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
    
}

?>