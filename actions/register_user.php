

<?php
// show errors
error_reporting(E_ALL);
ini_set('display_errors', 1); 
// echo "hello";
// include "config.php";
include '../db/config.php';

// Registration endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Retrieve and sanitize inputs
    $fname = isset($data['fname']) ? trim($data['fname']) : null;
    $lname = isset($data['lname']) ? trim($data['lname']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;
    $password = isset($data['password']) ? trim($data['password']) : null;
    // $role = 1;


    // Check if fields are empty
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        echo json_encode(["message" => "Missing fields"]);
        exit();
    }

    // Check for duplicate email
    $emailCheckQuery = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($emailCheckQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(200);
        // echo json_encode(["message" => "Email already registered"]);
        echo json_encode(["redirect" => "http://localhost/RECIPE_SHARING/view/login.html"]);


        // redirect to the login page
         // Redirect to the login page
        // header("Location: http://localhost/RECIPE_SHARING/view/login.html");
        
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
        // echo json_encode(["message" => "User registered successfully"]);
        echo json_encode(["redirect" => "http://localhost/RECIPE_SHARING/view/login.html"]);


        // redirect to the login page
        // Redirect to the login page
        // header("Location: http://localhost/RECIPE_SHARING/view/login.html");
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}



?>