

document.addEventListener("DOMContentLoaded", function () {

    // get the elements for username and password input fields
    // const usernameInput = document.getElementById("username")
    const passwordInput = document.getElementById("password")
    const emailInput = document.getElementById("email")

    
    // add event listeners to the input fields
    passwordInput.addEventListener("input", validatePassword)
    emailInput.addEventListener("input", validateEmail)



    // function validateUsername() {

    //     // get the element for the error message
    //     const errorMessage = document.getElementById("username-error-message");
        
    //     const username = usernameInput.value;
    //     const errors = [];

    //     // Check length
    //     if (username.length < 3 || username.length > 15) {
    //         errors.push("Username must be between 3 and 15 characters.");
    //     }

    //     // Check for allowed characters
    //     const regex = /^[a-zA-Z0-9_]+$/;
    //     if (!regex.test(username)) {
    //         errors.push("Username can only contain letters, numbers, and underscores.");
    //     }
    //     console.log(errors)

    //     // Display errors or valid message
    //     if (errors.length > 0) {
    //         errorMessage.textContent = errors.join(" ");
    //         errorMessage.classList.add("error");
    //         errorMessage.classList.remove("valid");
    //     } else {
    //         errorMessage.textContent = "Username is valid.";
    //         errorMessage.classList.add("valid");
    //         errorMessage.classList.remove("error");
    //     }
    // }


    function validatePassword(){
        const errorMessage = document.getElementById("password-error-message");
        const password = passwordInput.value
        const errors = []

        // check the length of the password
        if(password.length < 8){
            errors.push("Minimum length for password is 8 characters.")
        }

        // Check for at least one uppercase letter
        const hasUppercase = /[A-Z]/.test(password);
        if (!hasUppercase) {
            errors.push("Password must contain at least one uppercase letter.");
        }

        // Check for at least three digits
        const digitCount = (password.match(/\d/g) || []).length;
        if (digitCount < 3) {
            errors.push("Password must contain at least three digits.");
        }

        // Check for at least one special character
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        if (!hasSpecialChar) {
            errors.push("Password must contain at least one special character.");
        }

        // Display errors or valid message
        if (errors.length > 0) {
            errorMessage.textContent = errors.join(" ");
            errorMessage.classList.add("error");
            errorMessage.classList.remove("valid");
        } else {
            errorMessage.textContent = "Password is valid.";
            errorMessage.classList.add("valid");
            errorMessage.classList.remove("error");
        }
    }


    function validateEmail() {
        const email = emailInput.value;
        const errorElement = document.getElementById('email-error-message');
        const errors = []
        
        // Email validation regex pattern
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
        // Check for a valid email pattern
        if (!emailRegex.test(email)) {
            errors.push("Invalid email address.")

        } 

        // display the errors if present
        if(errors.length > 0){
            errorElement.textContent = errors.join(" ");
            errorElement.classList.remove("valid");
            errorElement.classList.add("error");
        }
        else{
            errorElement.textContent = "Email is valid.";
            errorElement.classList.remove("error");
            errorElement.classList.add("valid");
        }
    }
    
        
// form submission
const form = document.getElementById('register-form');
form.addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent default form submission

    const fname = document.getElementById('fname').value;
    const lname = document.getElementById('lname').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    // Check if any of the input fields are empty
    if (fname === "" || lname === "" || password === "" || email === "") {
        alert("All fields are required.");
        return;
    }

    // Check if any of the error messages are present
    if (document.querySelector('.error') !== null) {
        alert("Please correct the errors before submitting.");
        return;
    }

    // Convert input fields to JSON object
    const formData = {
        fname: fname,
        lname: lname,
        password: password,
        email: email
    };
    console.log(formData)

    try {
        // Post the JSON object to the server
        const response = await fetch('http://localhost/recipe_sharing/actions/register_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const result = await response.json();

            // alert(result.message || "Registration successful!");
            if (result.redirect) {
                console.log(result.redirect);
                window.location.href = result.redirect;
            }
            
            form.reset(); // Reset the form
        } else {
            const errorResult = await response.json();
            alert(errorResult.error || "An error occurred during registration.");
        }
    } catch (error) {
        console.error("Error during form submission:", error);
        alert("Failed to submit the form. Please try again.");
    }
});

        
        

        

    

});

