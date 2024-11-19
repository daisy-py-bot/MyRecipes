<?php
// Include database configuration
include 'config.php';
session_start();
header("Content-Type: application/json");

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get the user's role
$role = intval($_SESSION['role']); // Ensure role is an integer
$role = 1;

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // get the file content
    // $input = file_get_contents("php://input");
    // $data = json_decode($input, true); // Decode JSON as an associative array
    if ($role === 1) { // Check if the user has admin privileges
        // Validate the email parameter
        if (!isset($_GET['email']) || empty($_GET['email'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Email is required"]);
            exit();
        }

        // Sanitize the email input
        $userEmail = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

        // Prepare the SQL query to fetch user details
        $stmt = $conn->prepare("SELECT fname, lname, role, created_at FROM users WHERE email = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to prepare the query"]);
            exit();
        }

        $stmt->bind_param("s", $userEmail);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "User not found"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to execute the query"]);
        }

        // Clean up
        $stmt->close();
        $conn->close();
    } else {
        http_response_code(403); // Forbidden for non-admins
        echo json_encode(["success" => false, "message" => "Access denied"]);
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
