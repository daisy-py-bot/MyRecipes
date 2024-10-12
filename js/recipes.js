

// Toggle visibility of Add Recipe Form
document.getElementById('showAddFormBtn').addEventListener('click', function() {
    const addFormContainer = document.getElementById('addFormContainer');
    if (addFormContainer.style.display === 'none' || addFormContainer.style.display === '') {
        addFormContainer.style.display = 'block'; // Show the form
        this.textContent = 'Hide Form'; // Change button text
    } else {
        addFormContainer.style.display = 'none'; // Hide the form
        this.textContent = 'Add New Recipe'; // Revert button text
    }
});


// Add recipe functionality
document.getElementById('addRecipeForm').addEventListener('submit', function(event) {
    event.preventDefault();
    addRecipe();
});

// define the recipe function
function addRecipe() {
    const newRecipe = {
        id: recipes.length + 1,
        title: document.getElementById('title').value,
        author: document.getElementById('author').value,
        dateCreated: document.getElementById('dateCreated').value,
        ingredients: {
            origin: document.getElementById('origin').value,
            name: document.getElementById('ingredientName').value,
            nutritionalValue: document.getElementById('nutritionalValue').value,
            allergenInfo: document.getElementById('allergenInfo').value,
            shelfLife: document.getElementById('shelfLife').value,
            quantity: document.getElementById('quantity').value,
            unit: document.getElementById('unit').value,
        },
        prepTime: document.getElementById('prepTime').value,
        cookTime: document.getElementById('cookTime').value,
        servingSize: document.getElementById('servingSize').value,
        foodDescription: document.getElementById('foodDescription').value,
        calories: document.getElementById('calories').value,
        foodOrigin: document.getElementById('foodOrigin').value,
        instructions: document.getElementById('instructions').value,
        recipeImage: document.getElementById('recipeImage').value
    };

    recipes.push(newRecipe);
    renderRecipes();
    document.getElementById('addRecipeForm').reset();
    document.getElementById('addFormContainer').style.display = 'none'; // Hide the form after submission
    document.getElementById('showAddFormBtn').textContent = 'Add New Recipe'; // Reset button text
}

// delete recipes
function deleteRecipe(id) {
    
    const confirmation = confirm('Are you sure you want to delete this user?');
    if (confirmation) {
        recipes = recipes.filter(r => r.id !== id);
        renderRecipes();
    }
    
}


function viewRecipe(id) {
    console.log("Helloe")
    const recipe = recipes.find(r => r.id === id);
    if (!recipe) {
        console.log(`Recipe with ID ${id} not found`);
        return; // Exit the function if the recipe doesn't exist
    }
    else{
        console.log(recipe)
    }

    // Populate the modal with recipe details
    document.getElementById('viewTitle').textContent = recipe.title;
    console.log(recipe.title)
    document.getElementById('viewAuthor').textContent = recipe.author;
    document.getElementById('viewDateCreated').textContent = recipe.dateCreated;

    // Populate the modal with recipe details
    document.getElementById('viewTitle').textContent = recipe.title || 'N/A';
    document.getElementById('viewAuthor').textContent = recipe.author || 'N/A';
    document.getElementById('viewDateCreated').textContent = recipe.dateCreated || 'N/A';

    // Populate ingredients and other fields
    document.getElementById('viewOrigin').textContent = recipe.origin || 'N/A';
    document.getElementById('viewIngredientName').textContent = recipe.ingredientName || 'N/A';
    document.getElementById('viewNutritionalValue').textContent = recipe.nutritionalValue || 'N/A';
    document.getElementById('viewAllergenInfo').textContent = recipe.allergenInfo || 'N/A';
    document.getElementById('viewShelfLife').textContent = recipe.shelfLife || 'N/A';
    document.getElementById('viewQuantity').textContent = recipe.quantity || 'N/A';
    document.getElementById('viewUnit').textContent = recipe.unit || 'N/A';

    // Populate other fields
    document.getElementById('viewPrepTime').textContent = recipe.prepTime || 'N/A';
    document.getElementById('viewCookTime').textContent = recipe.cookTime || 'N/A';
    document.getElementById('viewServingSize').textContent = recipe.servingSize || 'N/A';
    document.getElementById('viewFoodDescription').textContent = recipe.foodDescription || 'N/A';
    document.getElementById('viewCalories').textContent = recipe.calories || 'N/A';
    document.getElementById('viewFoodOrigin').textContent = recipe.foodOrigin || 'N/A';
    document.getElementById('viewInstructions').textContent = recipe.instructions || 'N/A';
    document.getElementById('viewRecipeImage').src = recipe.image || 'path_to_default_image.jpg';


    // Show the modal
    document.getElementById('recipeDetailModal').style.display = 'block';
}

// Function to close the modal
document.getElementById('closeModalBtn').addEventListener('click', function() {
    document.getElementById('recipeDetailModal').style.display = 'none';
});



function editRecipe(id) {
    const recipe = recipes.find(r => r.id === id);

    document.getElementById('editRecipeId').value = recipe.id;
    document.getElementById('editTitle').value = recipe.title;
    document.getElementById('editAuthor').value = recipe.author;
    document.getElementById('editDateCreated').value = recipe.dateCreated;

    // Populate ingredients section
    // Populate the modal with recipe details
    document.getElementById('viewTitle').textContent = recipe.title || 'N/A';
    document.getElementById('viewAuthor').textContent = recipe.author || 'N/A';
    document.getElementById('viewDateCreated').textContent = recipe.dateCreated || 'N/A';

    // Populate ingredients and other fields
    document.getElementById('viewOrigin').textContent = recipe.origin || 'N/A';
    document.getElementById('viewIngredientName').textContent = recipe.ingredientName || 'N/A';
    document.getElementById('viewNutritionalValue').textContent = recipe.nutritionalValue || 'N/A';
    document.getElementById('viewAllergenInfo').textContent = recipe.allergenInfo || 'N/A';
    document.getElementById('viewShelfLife').textContent = recipe.shelfLife || 'N/A';
    document.getElementById('viewQuantity').textContent = recipe.quantity || 'N/A';
    document.getElementById('viewUnit').textContent = recipe.unit || 'N/A';

    // Populate other fields
    document.getElementById('viewPrepTime').textContent = recipe.prepTime || 'N/A';
    document.getElementById('viewCookTime').textContent = recipe.cookTime || 'N/A';
    document.getElementById('viewServingSize').textContent = recipe.servingSize || 'N/A';
    document.getElementById('viewFoodDescription').textContent = recipe.foodDescription || 'N/A';
    document.getElementById('viewCalories').textContent = recipe.calories || 'N/A';
    document.getElementById('viewFoodOrigin').textContent = recipe.foodOrigin || 'N/A';
    document.getElementById('viewInstructions').textContent = recipe.instructions || 'N/A';
    document.getElementById('viewRecipeImage').src = recipe.image || '../media/bread.png';

    document.getElementById('editFormContainer').style.display = 'block';
}
// Function to close the modal
document.getElementById('closeEditBtn').addEventListener('click', function() {
    document.getElementById('editFormContainer').style.display = 'none';
});

// function openModal() {
//     document.getElementById('recipeDetailModal').style.display = 'block';
// }

// function closeModal() {
//     document.getElementById('recipeDetailModal').style.display = 'none';
// }




function renderRecipes() {
    const tableBody = document.querySelector('#recipeTable tbody');
    tableBody.innerHTML = '';
    recipes.forEach(recipe => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td data-label="ID">${recipe.id}</td>
            <td data-label="Title">${recipe.title}</td>
            <td data-label="Author">${recipe.author}</td>
            <td data-label="Date Created">${recipe.dateCreated}</td>
            <td data-label="Actions">
                <button onclick="viewRecipe(${recipe.id})">View</button>
                <button onclick="editRecipe(${recipe.id})">Edit</button>
                <button onclick="deleteRecipe(${recipe.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// place dummy recipes
let recipes = [
    {
        id: 1,
        title: 'Jollof Rice',
        author: 'Daisy Tsenesa',
        dateCreated: '2024-10-06',
        origin: 'Nigeria',
        ingredientName: 'Rice, Tomatoes, Onion, Pepper',
        nutritionalValue: 'Carbohydrates, Fiber, Vitamins',
        allergenInfo: 'None',
        shelfLife: '3 days',
        quantity: '2',
        unit: 'cups',
        prepTime: 30,
        cookTime: 45,
        servingSize: 4,
        foodDescription: 'A flavorful one-pot dish with rich tomato sauce.',
        calories: 400,
        foodOrigin: 'West Africa',
        instructions: 'Cook rice with tomatoes and spices.',
        image: 'path_to_image.jpg' // Replace with actual image path
    },
    {
        id: 2,
        title: 'Sadza',
        author: 'Ruva Sadya',
        dateCreated: '2024-10-06',
        origin: 'Zimbabwe',
        ingredientName: 'Maize meal, Water',
        nutritionalValue: 'Carbohydrates',
        allergenInfo: 'Gluten-free',
        shelfLife: '1 day',
        quantity: '4',
        unit: 'cups',
        prepTime: 10,
        cookTime: 20,
        servingSize: 2,
        foodDescription: 'A staple food in Zimbabwe made from maize.',
        calories: 300,
        foodOrigin: 'Southern Africa',
        instructions: 'Boil water and add maize meal, stirring constantly.',
        image: 'path_to_image2.jpg' // Replace with actual image path
    }
];


renderRecipes();
