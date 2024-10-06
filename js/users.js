// Sample data
const users = [
    { id: 1, name: 'Daisy Tsenesa', email: 'daisy.t@gmail.com' },
    { id: 2, name: 'Ruva Sadya', email: 'ruva@gmail.com' },
    { id: 3, name: 'Simon Mazorodze', email: 'simon@gmail.com' }
];

// Load users into the table
function loadUsers() {
    const tableBody = document.querySelector("#userTable tbody");
    tableBody.innerHTML = ''; // Clear existing rows

    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td class="actions">
                <button class="view" onclick="viewUser(${user.id})">View</button>
                <button class="edit" onclick="editUser(${user.id})">Edit</button>
                <button class="delete" onclick="deleteUser(${user.id})">Delete</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
}

// View user details
function viewUser(id) {
    const user = users.find(u => u.id === id);
    alert(`ID: ${user.id}\nName: ${user.name}\nEmail: ${user.email}`);
}

// Open modal to edit user
function editUser(id) {
    const user = users.find(u => u.id === id);
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUserName').value = user.name;
    document.getElementById('editUserEmail').value = user.email;

    // Show modal
    document.getElementById('editModal').style.display = 'flex';
}

// Save updated user
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = parseInt(document.getElementById('editUserId').value);
    const updatedName = document.getElementById('editUserName').value;
    const updatedEmail = document.getElementById('editUserEmail').value;

    // Find user and update details
    const userIndex = users.findIndex(u => u.id === id);
    users[userIndex].name = updatedName;
    users[userIndex].email = updatedEmail;

    loadUsers(); // Reload the table

    // Close modal
    document.getElementById('editModal').style.display = 'none';
});

// Delete user
function deleteUser(id) {
    const confirmation = confirm('Are you sure you want to delete this user?');
    if (confirmation) {
        const userIndex = users.findIndex(u => u.id === id);
        users.splice(userIndex, 1); // Remove user from the array
        loadUsers(); // Reload the table
    }
}

// Close the modal
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
});

// Load users on page load
window.onload = loadUsers;
