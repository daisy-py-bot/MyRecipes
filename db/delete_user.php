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

$role = $_SESSION['role'];
// $role = 1;

// ------ DELETE request -------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // only super admin can delete users
    if ($role === 1) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); // Decode JSON as an associative array

        $user_to_delete_email = $data['email'];
        // echo "User to delete: " . $user_to_delete_id . "<br>";

        // $conn->query("SET FOREIGN_KEY_CHECKS=0");
        $sql = 'DELETE FROM users WHERE email = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_to_delete_email);

        if($stmt->execute()){

            http_response_code(200);
            echo json_encode(['success' => 'User deleted successfully']);
        } 
        else {
            http_response_code(200);
            echo json_encode(['failure' => 'User cannot be deleted', 'error' => $stmt->error]);
        }
    } 
    else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
    // $conn->query("SET FOREIGN_KEY_CHECKS=1");
}


?>
