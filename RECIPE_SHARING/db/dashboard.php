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
echo "User role: ". $_SESSION['role'] . "<br>";
echo "User id in session: " . $_SESSION['user_id'] . "<br>";

// $role = $_SESSION['role'];
$role = 2;

// ------ GET request -------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($role === 1) {
        // permissions for the super admin
        echo "Get the dashboard for super admin. <br>";
        getSuperAdminDashboard($conn);

    } 
    elseif ($role === 2) {
        // permissions for the regular admin
        echo "Get the dashboard for regular admin. <br>";
        // $user_id_in_session = $_SESSION['user_id'];
        $user_id_in_session = 2;
        getRegularAdminDashboard($conn, $user_id_in_session);
    } 
    else {
        // permission denied
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
}

// ------ DELETE request -------
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // only super admin can delete users
    if ($role === 1) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); // Decode JSON as an associative array
        $user_to_delete_id = $data['user_id'];
        echo "User to delete: " . $user_to_delete_id . "<br>";

        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        $sql = 'DELETE FROM users WHERE user_id = ?';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_to_delete_id);

        if($stmt->execute()){

            http_response_code(200);
            echo json_encode(['success' => 'User deleted successfully']);
        } 
        else {
            http_response_code(402);
            echo json_encode(['failure' => 'User not deleted', 'error' => $stmt->error]);
        }
    } 
    else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
}



// -------functions for the 'GET' request---------

function getSuperAdminDashboard($conn) {
    // echo "In the super admin function: <br>";
    // Super Admin Analytics
    $totalUsers = getTotalUsers($conn);
    // echo "Total users: " . $totalUsers;
    $totalRecipes = getTotalRecipes($conn);
    // echo "Total recipes: " . $totalRecipes;
    // $pendingApprovals = getPendingApprovals($conn);
    // echo "Pending approvals: " . $pendingApprovals;
    
    // User Management
    $allUsers = getAllUsers($conn);
    // echo "All users: " . $allUsers;

    // Recipe Overview
    $recentRecipes = getRecentRecipesFromAllUsers($conn);

    // Prepare response
    $response = [
        'analytics' => [
            'totalUsers' => $totalUsers,
            'totalRecipes' => $totalRecipes,
            // 'pendingApprovals' => $pendingApprovals,
        ],
        'userManagement' => $allUsers,
        'recipeOverview' => [
            'recentRecipes' => $recentRecipes,
            'totalRecipeCount' => $totalRecipes,
        ]
    ];

    // Analytics charts
    // echo "Sending the data for super admin";
    echo json_encode($response);
}


function getRegularAdminDashboard($conn, $adminId) {
    // Personal Analytics for Regular Admin
    $personalRecipeCount = getRecipeCountAddedByAdmin($conn, $adminId);
    $recentUserRecipes = getRecentUserRecipesSubmitted($conn, $adminId);

    // Prepare response
    $response = [
        'personalAnalytics' => [
            'totalRecipes' => $personalRecipeCount,
        ],
        'recipeManagement' => $recentUserRecipes,
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

function getTotalRecipes($conn) {
    $query = "SELECT COUNT(*) as total FROM recipes";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// is it user or food approval?
// function getPendingApprovals($conn) {
//     $query = "SELECT COUNT(*) as pending FROM users WHERE status = 'pending'";
//     $result = $conn->query($query);
//     $row = $result->fetch_assoc();
//     return $row['pending'];
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

function getRecentRecipesFromAllUsers($conn) {
    $sql = 
    'SELECT
    u.fname AS author_first_name,
    u.lname AS author_last_name,
    f.name AS food_name,
    f.origin AS food_origin,
    f.type AS food_type,
    f.is_healthy AS is_healthy,
    f.instructions AS food_instructions,
    f.description AS food_description,
    f.preparation_time AS preparation_time,
    f.cooking_time AS cooking_time,
    f.serving_size AS serving_size,
    f.calories_per_serving AS calories_per_serving,
    r.recipe_id AS recipe_id,
    r.quantity AS recipe_quantity,
    r.unit AS recipe_unit,
    r.optional AS is_optional,
    r.created_at AS recipe_created_at
    FROM
        recipes r
    JOIN
        foods f ON r.food_id = f.food_id
    JOIN
        users u ON f.created_by = u.user_id
    ORDER BY
        r.created_at DESC LIMIT 10;'; //  -- To show the most recently added foods and recipes

    $result = $conn->query($sql);
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;
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


function getRecentUserRecipesSubmitted($conn, $adminId) {
    $sql = 
    'SELECT
    u.fname AS author_first_name,
    u.lname AS author_last_name,
    f.name AS food_name,
    f.origin AS food_origin,
    f.type AS food_type,
    f.is_healthy AS is_healthy,
    f.instructions AS food_instructions,
    f.description AS food_description,
    f.preparation_time AS preparation_time,
    f.cooking_time AS cooking_time,
    f.serving_size AS serving_size,
    f.calories_per_serving AS calories_per_serving,
    r.recipe_id AS recipe_id,
    r.quantity AS recipe_quantity,
    r.unit AS recipe_unit,
    r.optional AS is_optional,
    r.created_at AS recipe_created_at
    FROM
        recipes r
    JOIN
        foods f ON r.food_id = f.food_id
    JOIN
        users u ON f.created_by = u.user_id
    WHERE 
        u.user_id = ?
    ORDER BY
        r.created_at DESC LIMIT 10;'; //  -- To show the most recently added foods and recipes

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;
}

function ListAllRecipesByAdmin($conn, $adminId){
    $sql = 
    'SELECT
    u.fname AS author_first_name,
    u.lname AS author_last_name,
    f.name AS food_name,
    f.origin AS food_origin,
    f.type AS food_type,
    f.is_healthy AS is_healthy,
    f.is_approved AS approved_status,
    f.instructions AS food_instructions,
    f.description AS food_description,
    f.preparation_time AS preparation_time,
    f.cooking_time AS cooking_time,
    f.serving_size AS serving_size,
    f.calories_per_serving AS calories_per_serving,
    r.recipe_id AS recipe_id,
    r.quantity AS recipe_quantity,
    r.unit AS recipe_unit,
    r.optional AS is_optional,
    r.created_at AS recipe_created_at
    FROM
        recipes r
    JOIN
        foods f ON r.food_id = f.food_id
    JOIN
        users u ON f.created_by = u.user_id
    WHERE 
        u.user_id = ?;';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;

}

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

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['approved_recipe_count'];
    
}
?>
