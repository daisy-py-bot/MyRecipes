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

let recipes = [
    { id: 1, title: 'Jollof Rice', author: 'Daisy Tsenesa', dateCreated: '2024-10-06' },
    { id: 2, title: 'Sadza', author: 'Ruva Sadya', dateCreated: '2024-10-06' },
];

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

function addRecipe() {
    const newRecipe = {
        id: recipes.length + 1,
        title: document.getElementById('title').value,
        author: document.getElementById('author').value,
        dateCreated: document.getElementById('dateCreated').value,
    };
    recipes.push(newRecipe);
    renderRecipes();
    document.getElementById('addRecipeForm').reset();
    document.getElementById('addFormContainer').style.display = 'none'; // Hide the form after submission
    document.getElementById('showAddFormBtn').textContent = 'Add New Recipe'; // Reset button text
}

function viewRecipe(id) {
    const recipe = recipes.find(r => r.id === id);
    alert(`Title: ${recipe.title}\nAuthor: ${recipe.author}\nDate Created: ${recipe.dateCreated}`);
}

function editRecipe(id) {
    const recipe = recipes.find(r => r.id === id);
    document.getElementById('editRecipeId').value = recipe.id;
    document.getElementById('editTitle').value = recipe.title;
    document.getElementById('editAuthor').value = recipe.author;
    document.getElementById('editDateCreated').value = recipe.dateCreated;
    document.getElementById('editFormContainer').style.display = 'block';
}

function updateRecipe() {
    const id = document.getElementById('editRecipeId').value;
    const updatedRecipe = {
        id: parseInt(id),
        title: document.getElementById('editTitle').value,
        author: document.getElementById('editAuthor').value,
        dateCreated: document.getElementById('editDateCreated').value,
    };
    const index = recipes.findIndex(r => r.id === parseInt(id));
    recipes[index] = updatedRecipe;
    renderRecipes();
    document.getElementById('editFormContainer').style.display = 'none';
}

function deleteRecipe(id) {
    recipes = recipes.filter(r => r.id !== id);
    renderRecipes();
}

renderRecipes();
