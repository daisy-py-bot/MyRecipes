

document.addEventListener("DOMContentLoaded", function () {

    // get the elements for username and password input fields
    const usernameInput = document.getElementById("username")
    const passwordInput = document.getElementById("password")
    
    // add event listeners to the input fields
    usernameInput.addEventListener("input", validateUsername);
    passwordInput.addEventListener("input", validatePassword)

    function validateUsername() {

        // get the element for the error message
        const errorMessage = document.getElementById("username-error-message");
        
        const username = usernameInput.value;
        const errors = [];

        // Check length
        if (username.length < 3 || username.length > 15) {
            errors.push("Username must be between 3 and 15 characters.");
        }

        // Check for allowed characters
        const regex = /^[a-zA-Z0-9_]+$/;
        if (!regex.test(username)) {
            errors.push("Username can only contain letters, numbers, and underscores.");
        }
        console.log(errors)

        // Display errors or valid message
        if (errors.length > 0) {
            errorMessage.textContent = errors.join(" ");
            errorMessage.classList.add("error");
            errorMessage.classList.remove("valid");
        } else {
            errorMessage.textContent = "Username is valid.";
            errorMessage.classList.add("valid");
            errorMessage.classList.remove("error");
        }
    }


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
        

    

});

