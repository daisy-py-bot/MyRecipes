<?php
// Include database configuration
include 'config.php';
session_start();


// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// $role = $_SESSION['role'];
$role = 1;

// Main endpoint to get dashboard content based on role
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($role === 1) {
        getSuperAdminDashboard($conn);
    } 
    else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
}


// ------ DELETE request -------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if ($role === 1) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); // Decode JSON as an associative array
        $user_to_delete_id = $data['user_id'];

        echo "User to delete: " . $user_to_delete_id . "<br>";
        $sql = 'DELETE FROM users WHERE user_id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_to_delete_id);
        if($stmt->execute()){
            http_response_code(200);
            echo json_encode(['success' => 'User deleted successfully']);
        }
        else{
            http_response_code(402);
            echo json_encode(['failure' => 'User not deleted']);
        }
    } 
    else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
}





// -------functions for the 'GET' request---------

function getSuperAdminDashboard($conn) {
    // Super Admin Analytics
    $totalUsers = getTotalUsers($conn);
    
    // User Management
    $allUsers = getAllUsers($conn);

    // Prepare response
    $response = [
        'analytics' => [
            'totalUsers' => $totalUsers
        ],
        'userManagement' => $allUsers
    ];

    echo json_encode($response);
}




// Helper functions for each section of the dashboard
function getTotalUsers($conn) {
    $query = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}


function getAllUsers($conn) {
    $query = "SELECT CONCAT(fname, ' ', lname) as fullname, email, role, created_at FROM users";
    $result = $conn->query($query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}






?>
