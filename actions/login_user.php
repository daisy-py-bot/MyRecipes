<?php
// Show errors for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1); 

// Include configuration file
include '../db/config.php';

// opcache_reset();
// Start a session
session_start();

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Retrieve and sanitize inputs
    $email = isset($data['email']) ? trim($data['email']) : null;
    $password = isset($data['password']) ? trim($data['password']) : null;

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid email format"]);
        exit();
    }

    // Check for empty fields
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required"]);
        exit();
    }

    // Prepare SQL query to check if user is registered
    $emailCheckQuery = "SELECT user_id, fname, lname, password, role FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($emailCheckQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user is found, verify password
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['lname'] = $row['lname'];
                $_SESSION['role'] = $row['role'];

                // echo session_id();
                // echo "Your information: ". $_SESSION['user_id'] . " " . $_SESSION['fname'] . " " . $_SESSION['lname'] . " " . $_SESSION['role'];
                http_response_code(200);
                // echo json_encode(['message' => 'Account verified. Login successful']);
                echo json_encode(["redirect" => "http://localhost/RECIPE_SHARING/view/admin/dashboard.php", "role" => $row['role']]);
            } 
            else {
                http_response_code(401);
                echo json_encode(['message' => 'Password incorrect']);
            }
        } 
        else {
            // User not registered
            http_response_code(404);
            echo json_encode(['message' => "User not registered"]);
        }
        $stmt->close();
    } 
    else {
        // Handle SQL prepare error
        http_response_code(500);
        echo json_encode(['message' => 'Server error, please try again later']);
    }
    
    $conn->close();
}
?>
