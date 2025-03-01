<?php 

session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        // redirect the user to the login page if they are not logged in
        header("Location: ../login.html");
        exit();
    }

    $role = (int)$_SESSION['role'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/recipes.css">
    <link rel="stylesheet" href="../assets/css/global_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <a href="#" class="menu-logo" >
            <img src="../assets/images/mylogo.png" alt="logo">
         </a>
        <h2>Admin Panel</h2>
        <ul>

            <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <!-- disable the access to the users page if the user is not a super admin -->
            <?php if($role === 1): ?>
            <li><a href="users.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <?php endif; ?>
            <!-- <li><a href="users.html"><i class="fas fa-users"></i><span>Users</span></a></li> -->
            <li><a href="recipes.php"><i class="fas fa-utensils"></i><span>Recipes</span></a></li>
            <li><a href="#"><i class="fas fa-cog"></i><span>Settings</span></a></li>
            <li><a href="../index.php" target="_blank"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li><a href="logout.html"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>


    <div class="main-content">
        <!-- User header -->
        <div class="header">
            <h1 style="text-align: center;">Recipes Management</h1>
            <p>Manage your recipes</p>
        </div>


        <div class="table-container">
            <!-- Recipe Table -->
            <table id="recipeTable" class="responsive-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td data-label="ID">1</td>
                        <td data-label="Title">Spaghetti Carbonara</td>
                        <td data-label="Author">John Doe</td>
                        <td data-label="Date Created">2024-10-06</td>
                        <td data-label="Actions">
                            <button class="view">View</button>
                            <button class="edit">Edit</button>
                            <button class="delete">Delete</button>
                        </td>
                    </tr> -->
                </tbody>
            </table>
        </div>
        
        <!-- Recipe Detail Modal (Hidden by default) -->
        <div class="modal" id="recipeDetailModal">
            <div class="modal-content">
                <h3>Recipe Details</h3>
                <button id="closeModalBtn" class="close-btn">Close</button>
                
                <p><strong>Recipe Title:</strong> <span id="viewTitle"></span></p>
                <p><strong>Author:</strong> <span id="viewAuthor"></span></p>
                <p><strong>Date Created:</strong> <span id="viewDateCreated"></span></p>

                <!-- <h4>Ingredients</h4> -->
                <p><strong>Origin:</strong> <span id="viewOrigin"></span></p>
                <p><strong>Name:</strong> <span id="viewIngredientName"></span></p>
                <p><strong>Nutritional Value:</strong> <span id="viewNutritionalValue"></span></p>
                <p><strong>Allergen Information:</strong> <span id="viewAllergenInfo"></span></p>
                <p><strong>Shelf Life:</strong> <span id="viewShelfLife"></span></p>
                <p><strong>Quantity:</strong> <span id="viewQuantity"></span></p>
                <p><strong>Unit:</strong> <span id="viewUnit"></span></p>

                <h4>Other Information</h4>
                <p><strong>Preparation Time:</strong> <span id="viewPrepTime"></span> minutes</p>
                <p><strong>Cooking Time:</strong> <span id="viewCookTime"></span> minutes</p>
                <p><strong>Serving Size:</strong> <span id="viewServingSize"></span></p>
                <p><strong>Food Description:</strong> <span id="viewFoodDescription"></span></p>
                <p><strong>Calories per Serving:</strong> <span id="viewCalories"></span></p>
                <p><strong>Food Origin:</strong> <span id="viewFoodOrigin"></span></p>
                <p><strong>Instructions:</strong> <span id="viewInstructions"></span></p>
                <p><strong>Recipe Image:</strong> <img id="viewRecipeImage" src="" alt="Recipe Image" style="width: 100%; max-width: 300px;" /></p>
            </div>
        </div>

        <!-- Button to show the Add Recipe form -->
        <button id="showAddFormBtn" class="toggle-btn">Add New Recipe</button>


        <!-- Add Recipe Form (extended with more fields) -->
        <div class="form-container" id="addFormContainer" style="display: none;">
            <h3>Add New Recipe</h3>
            <form id="addRecipeForm">
                <label for="title">Recipe Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>

                <label for="dateCreated">Date Created:</label>
                <input type="date" id="dateCreated" name="dateCreated" >

                <!-- Ingredient fields -->
                <div id="ingredientsContainer">
                    <h4>Ingredients</h4>
                    <div class="ingredient-item">
                        <label for="origin">Origin:</label>
                        <input type="text" id="origin" name="origin" >

                        <label for="ingredientName">Name:</label>
                        <input type="text" id="ingredientName" name="ingredientName" >

                        <label for="nutritionalValue">Nutritional Value:</label>
                        <input type="text" id="nutritionalValue" name="nutritionalValue" >

                        <label for="allergenInfo">Allergen Information:</label>
                        <input type="text" id="allergenInfo" name="allergenInfo" >

                        <label for="shelfLife">Shelf Life:</label>
                        <input type="text" id="shelfLife" name="shelfLife" >

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" >

                        <label for="unit">Unit:</label>
                        <input type="text" id="unit" name="unit" >
                    </div>
                </div>

                <label for="prepTime">Preparation Time (minutes):</label>
                <input type="number" id="prepTime" name="prepTime" >

                <label for="cookTime">Cooking Time (minutes):</label>
                <input type="number" id="cookTime" name="cookTime" >

                <label for="servingSize">Serving Size:</label>
                <input type="text" id="servingSize" name="servingSize" >

                <label for="foodDescription">Food Description:</label>
                <textarea id="foodDescription" name="foodDescription" required></textarea>

                <label for="calories">Calories per Serving:</label>
                <input type="number" id="calories" name="calories" >

                <label for="foodOrigin">Food Origin:</label>
                <input type="text" id="foodOrigin" name="foodOrigin" >

                <label for="instructions">Instructions:</label>
                <textarea id="instructions" name="instructions" required></textarea>

                <label for="recipeImage">Recipe Image URL:</label>
                <input type="text" id="recipeImage" name="recipeImage" >

                <button type="submit">Add Recipe</button>
            </form>
        </div>



        <!-- Edit Recipe Form  -->
        <div class="form-container" id="editFormContainer" style="display: none;">
            <h3>Edit Recipe</h3>
            <form id="editRecipeForm">
                <input type="hidden" id="editRecipeId" name="id">
                
                <label for="editTitle">Recipe Title:</label>
                <input type="text" id="editTitle" name="title" required>

                <label for="editAuthor">Author:</label>
                <input type="text" id="editAuthor" name="author" required>

                <label for="editDateCreated">Date Created:</label>
                <input type="date" id="editDateCreated" name="dateCreated" required>

                <!-- Ingredients fields for editing -->
                <div id="editIngredientsContainer">
                    <h4>Ingredients</h4>
                    <div class="ingredient-item">
                        <label for="editOrigin">Origin:</label>
                        <input type="text" id="editOrigin" name="origin" required>

                        <label for="editIngredientName">Name:</label>
                        <input type="text" id="editIngredientName" name="ingredientName" required>

                        <label for="editNutritionalValue">Nutritional Value:</label>
                        <input type="text" id="editNutritionalValue" name="nutritionalValue" >

                        <label for="editAllergenInfo">Allergen Information:</label>
                        <input type="text" id="editAllergenInfo" name="allergenInfo" >

                        <label for="editShelfLife">Shelf Life:</label>
                        <input type="text" id="editShelfLife" name="shelfLife" >

                        <label for="editQuantity">Quantity:</label>
                        <input type="number" id="editQuantity" name="quantity" >

                        <label for="editUnit">Unit:</label>
                        <input type="text" id="editUnit" name="unit" >
                    </div>
                </div>

                <label for="editPrepTime">Preparation Time (minutes):</label>
                <input type="number" id="editPrepTime" name="prepTime" >

                <label for="editCookTime">Cooking Time (minutes):</label>
                <input type="number" id="editCookTime" name="cookTime" >

                <label for="editServingSize">Serving Size:</label>
                <input type="text" id="editServingSize" name="servingSize" >

                <label for="editFoodDescription">Food Description:</label>
                <textarea id="editFoodDescription" name="foodDescription" required></textarea>

                <label for="editCalories">Calories per Serving:</label>
                <input type="number" id="editCalories" name="calories" >

                <label for="editFoodOrigin">Food Origin:</label>
                <input type="text" id="editFoodOrigin" name="foodOrigin" >

                <label for="editInstructions">Instructions:</label>
                <textarea id="editInstructions" name="instructions" required></textarea>

                <label for="editRecipeImage">Recipe Image URL:</label>
                <input type="text" id="editRecipeImage" name="recipeImage" >

                <button type="submit">Update Recipe</button>
                <button id="closeEditBtn" class="close-btn">Close</button>
            </form>
        </div>



        
    </div>
    <script src="../assets/js/recipes.js"></script>

</body>

</html>
