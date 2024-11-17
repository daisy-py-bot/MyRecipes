

<?php
// show errors
error_reporting(E_ALL);
ini_set('display_errors', 1); 
// echo "hello";
include "config.php";

// Registration endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Retrieve and sanitize inputs
    $fname = isset($data['fname']) ? trim($data['fname']) : null;
    $lname = isset($data['lname']) ? trim($data['lname']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;
    $password = isset($data['password']) ? trim($data['password']) : null;
    $role = 2;


    // Check if fields are empty
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        echo json_encode(["message" => "All fields are required"]);
        exit();
    }

    // Check for duplicate email
    $emailCheckQuery = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($emailCheckQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(402);
        echo json_encode(["message" => "Email already registered"]);
        exit();
    }
    $stmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insertQuery = "INSERT INTO users (fname, lname, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssi", $fname, $lname, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}



?>