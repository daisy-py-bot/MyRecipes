
// Open modal to edit user
// function editUser(id) {
//     const user = users.find(u => u.id === id);
//     document.getElementById('editUserId').value = user.id;
//     document.getElementById('editUserName').value = user.name;
//     document.getElementById('editUserEmail').value = user.email;

//     // Show modal
//     document.getElementById('editModal').style.display = 'flex';
// }

// Validate email using regex
function validateEmail(email) {   
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
}

// Add event listener for form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = parseInt(document.getElementById('editUserId').value);
    const updatedName = document.getElementById('editUserName').value;
    const updatedEmail = document.getElementById('editUserEmail').value;

    const emailErrorElement = document.getElementById('email-error-message');

    // Clear previous error message
    emailErrorElement.textContent = '';

    // Validate email before updating
    if (!validateEmail(updatedEmail)) {
        emailErrorElement.textContent = 'Invalid email address.';
        emailErrorElement.classList.add('error');
    } else {
        emailErrorElement.textContent = '';
        emailErrorElement.classList.remove('error');

        // Find and update the user
        const userIndex = users.findIndex(u => u.id === id);
        users[userIndex].name = updatedName;
        users[userIndex].email = updatedEmail;

        loadUsers(); // Reload the table with updated data

        // Close the modal
        document.getElementById('editModal').style.display = 'none';
    }
});


// function viewUser(id) {
//     const user = users.find(u => u.id === id);
//     if (!user) {
//         alert(`User with ID ${id} not found`);
//         return; // Exit the function if the user doesn't exist
//     }

//     // Create a message with user information
//     const userInfo = `
//         User ID: ${user.id}
//         Name: ${user.name}
//         Email: ${user.email}
//     `;

//     // Show the alert with user information
//     alert(userInfo);
// }

// Delete user

// function deleteUser(id) {
//     const confirmation = confirm('Are you sure you want to delete this user?');
//     if (confirmation) {
//         const userIndex = users.findIndex(u => u.id === id);
//         users.splice(userIndex, 1); // Remove user from the array
//         loadUsers(); // Reload the table
//     }
// }



// Close the modal
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('editModal').style.display = 'none';
});



