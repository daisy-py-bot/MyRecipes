document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded and parsed.");
    // Fetch user data from the server
    fetch("http://localhost/recipe_sharing/db/get_users.php", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to fetch users");
            }
            return response.json(); // Parse JSON response
        })
        .then((data) => {
            var users = data; // Assuming the JSON structure has this key
            populateUserTable(users);
        })
        .catch((error) => {
            console.error("Error fetching users:", error);
        });
});

// function populateUserTable(users) {
//     const userTableBody = document.querySelector("#userTable tbody");

//     users.forEach((user, index) => {
//         // Create a table row
//         console.log(user);
//         const row = document.createElement("tr");
//         row.setAttribute("data-email", user.email); // Add data attribute for email

//         // Create table cells for user details
//         const idCell = document.createElement("td");
//         idCell.textContent = index + 1; // Incremental ID

//         const nameCell = document.createElement("td");
//         nameCell.textContent = user.fullname;

//         const emailCell = document.createElement("td");
//         emailCell.textContent = user.email;

//         const actionsCell = document.createElement("td");
//         actionsCell.innerHTML = `
//             <button onclick="viewUser(${'user.email'})">View</button>
//             <button onclick="editUser(${'user.email'})">Edit</button>
//             <button onclick="deleteUser(${'user.email'})">Delete</button>
//         `;

//         // Append cells to the row
//         row.appendChild(idCell);
//         row.appendChild(nameCell);
//         row.appendChild(emailCell);
//         row.appendChild(actionsCell);

//         // Append row to the table body
//         userTableBody.appendChild(row);
//     });
// }


// Function to delete a user


function populateUserTable(users) {
    const userTableBody = document.querySelector("#userTable tbody");

    users.forEach((user, index) => {
        const row = document.createElement("tr");
        row.setAttribute("data-email", user.email); // Add data attribute for email

        const idCell = document.createElement("td");
        idCell.textContent = index + 1;

        const nameCell = document.createElement("td");
        nameCell.textContent = user.fullname;

        const emailCell = document.createElement("td");
        emailCell.textContent = user.email;

        const actionsCell = document.createElement("td");

        const viewButton = document.createElement("button");
        viewButton.textContent = "View";
        viewButton.addEventListener("click", () => viewUser(user.email));

        const editButton = document.createElement("button");
        editButton.textContent = "Edit";
        editButton.addEventListener("click", () => editUser(user.email));

        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Delete";
        deleteButton.addEventListener("click", () => deleteUser(user.email));

        actionsCell.appendChild(viewButton);
        actionsCell.appendChild(editButton);
        actionsCell.appendChild(deleteButton);

        row.appendChild(idCell);
        row.appendChild(nameCell);
        row.appendChild(emailCell);
        row.appendChild(actionsCell);

        userTableBody.appendChild(row);
    });
}


function deleteUser(email) {
    if (confirm(`Are you sure you want to delete the user with email: ${email}?`)) {
        fetch("http://localhost/recipe_sharing/db/delete_user.php", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ email: email }), // Send the email to the server
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Failed to delete user");
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert("User deleted successfully!");
                    // Remove the user's row from the table
                    const userRow = document.querySelector(
                        `tr[data-email="${email}"]`
                    );
                    if (userRow) {
                        userRow.remove();
                    }
                } else {
                    alert("Failed to delete user. Please try again.");
                }
            })
            .catch((error) => {
                console.error("Error deleting user:", error);
            });
    }
}



// Function to view user details by email
function viewUser(email) {
    // Fetch user details using their email
    fetch(`http://localhost/recipe_sharing/db/get_one_user.php?email=${encodeURIComponent(email)}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to fetch user details");
            }
            return response.json(); // Parse JSON response
        })
        .then((data) => {
            // Display user information in a popup
            const userInfo = `
                Full Name: ${data.lname} ${data.fname}
                Email: ${data.created_at}
                Role: ${data.role}
            `;
            alert(userInfo);
        })
        .catch((error) => {
            console.error("Error fetching user details:", error);
            alert("Could not fetch user details. Please try again.");
        });
}


// Function to edit user
function editUser(userId) {
    alert("You cannot edit this user for now.");
}
