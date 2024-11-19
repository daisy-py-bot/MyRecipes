

<?php

// function getAllUsers($conn) {
//     // Super Admin Analytics
//     $totalUsers = getTotalUsers($conn);
    
//     // User Management
//     $allUsers = getAllUsers($conn);

//     // Prepare response
//     $response = [
//         'analytics' => [
//             'totalUsers' => $totalUsers
//         ],
//         'userManagement' => $allUsers
//     ];

//     echo json_encode($response);
// }

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