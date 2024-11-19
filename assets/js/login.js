document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded and parsed.");

    // Get the elements for username and password input fields
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    // Add event listeners to the input fields
    emailInput.addEventListener("input", validateEmail);
    passwordInput.addEventListener("input", validatePassword);

    function validatePassword() {
        const errorMessage = document.getElementById("password-error-message");
        const password = passwordInput.value;
        const errors = [];

        if (password.length < 8) {
            errors.push("Minimum length for password is 8 characters.");
        }
        const hasUppercase = /[A-Z]/.test(password);
        if (!hasUppercase) {
            errors.push("Password must contain at least one uppercase letter.");
        }
        const digitCount = (password.match(/\d/g) || []).length;
        if (digitCount < 3) {
            errors.push("Password must contain at least three digits.");
        }
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        if (!hasSpecialChar) {
            errors.push("Password must contain at least one special character.");
        }

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
        const errors = [];

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailRegex.test(email)) {
            errors.push("Invalid email address.");
        }

        if (errors.length > 0) {
            errorElement.textContent = errors.join(" ");
            errorElement.classList.remove("valid");
            errorElement.classList.add("error");
        } else {
            errorElement.textContent = "Email is valid.";
            errorElement.classList.remove("error");
            errorElement.classList.add("valid");
        }
    }

    // Form submission
    const form = document.getElementById('login-user-form');
    form.addEventListener('submit', async function (event) {
        event.preventDefault(); // Prevent default form submission

        const password = document.getElementById('password').value;
        const email = document.getElementById('email').value;

        if (password === "" || email === "") {
            alert("All fields are required.");
            return;
        }

        if (document.querySelector('.error') !== null) {
            alert("Please correct the errors before submitting.");
            return;
        }

        const formData = {
            password: password,
            email: email
        };
        console.log(formData);

        try {
            const response = await fetch('http://localhost/recipe_sharing/actions/login_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            if (response.ok) {
                const result = await response.json();
                if (result.redirect) {
                    // alert("Login successful! Your role is: " + result.role);
                    window.location.href = result.redirect;
                }
                form.reset();
            } else {
                const errorResult = await response.json();
                showError(errorResult.error || "An error occurred during login.");
            }
        } catch (error) {
            console.error("Error during form submission:", error);
            alert("Failed to submit the form. Please try again.");
        }
    });

    function showError(message) {
        const errorElement = document.getElementById('form-error-message');
        errorElement.textContent = message;
        errorElement.classList.add('error');
    }
});
