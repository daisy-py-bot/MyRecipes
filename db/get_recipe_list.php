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


// $role = 2;


// ------ GET request -------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($role === 1) {
        // get all the recipes
        http_response_code(200);
        echo json_encode(['recipes' => getAllRecipes($conn)]);

    } 
    elseif ($role === 2) {
        // get only recipes created by the admin
        http_response_code(200);
        echo json_encode(['recipes' => ListAllRecipesByAdmin($conn, $_SESSION['user_id'])]);

    } 
    else {
        // permission denied
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: No access to the dashboard']);
    }
}


function getAllRecipes($conn) {
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
        r.created_at DESC;'; //  -- To show the most recently added foods and recipes

    $result = $conn->query($sql);
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
    return $recipes;
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


// Close the database connection
$conn->close();
?>
