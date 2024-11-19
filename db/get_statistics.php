<?php
// display the errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

// ------ GET request -------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($role === 1) {
        // get stats for super admin
        http_response_code(200);
        echo json_encode(superAdminStats($conn));
    } 
    elseif ($role === 2) {
        // get stats for regular admin
        http_response_code(200);
        echo json_encode(regularAdminStats($conn, $_SESSION['user_id']));
    } 
    else {
        // permission denied
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
}


// -------functions for the 'GET' request---------

function superAdminStats($conn) {
    // Prepare response
    $response = [
            'totalUsers' => getTotalUsers($conn),
            'totalRecipes' => getTotalRecipes($conn),
            'totalApprovedRecipes' => countAllRecipesApproved($conn),
        
    ];

    return $response;

}


function regularAdminStats($conn, $adminId) {
    // Prepare response
    $response = [
        'totalRecipes' => getRecipeCountAddedByAdmin($conn, $adminId),
        'totalApprovedRecipes' => countUserRecipesApproved($conn, $adminId),
        'totalApprovedRecipes' => countAllRecipesApproved($conn),
    ];
    return $response;

}


// get the total number of users
function getTotalUsers($conn) {
    $query = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// get the total number of recipes
function getTotalRecipes($conn) {
    $query = "SELECT COUNT(*) as total FROM recipes";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}


// get the number of recipes created by the admin
function getRecipeCountAddedByAdmin($conn, $adminId) {
    $sql = 'SELECT 
        f.created_by AS user_id,
        COUNT(r.recipe_id) AS recipe_count
        FROM 
            foods f
        JOIN 
            recipes r ON f.food_id = r.food_id
        WHERE 
            f.created_by = ? 
        GROUP BY 
            f.created_by;
        ';
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['recipe_count'];
}

// get the number of recipes approved by the admin for that user
function countUserRecipesApproved($conn, $adminId){
    $sql = 'SELECT 
    f.created_by AS user_id,
        COUNT(r.recipe_id) AS approved_recipe_count
    FROM 
        foods f
    JOIN 
        recipes r ON f.food_id = r.food_id
    WHERE 
        f.created_by = ? 
        AND f.is_approved = 1 
    GROUP BY 
        f.created_by;
    ';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); 
    return $row['approved_recipe_count'];

}

function countAllRecipesApproved($conn){
    $sql = 'SELECT 
                COUNT(r.recipe_id) AS approved_recipe_count
            FROM 
                foods f
            JOIN 
                recipes r ON f.food_id = r.food_id
            WHERE 
                f.is_approved = 1;';

    // Execute the query
    $result = $conn->query($sql);

    // Check for query errors
    if (!$result) {
        throw new Exception("SQL Error: " . $conn->error);
    }

    // Fetch the result
    $row = $result->fetch_assoc();
    return $row['approved_recipe_count'];
}

?>
