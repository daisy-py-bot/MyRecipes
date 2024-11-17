    // // Example number of recipes per month (12 months)
    // const recipeData = [120, 30, 67, 120, 120, 67, 80, 50, 78, 34, 50, 1]; // Updated to match HTML structure


    document.addEventListener('DOMContentLoaded', function () {
        // Get all the bars
        const bars = document.querySelectorAll('.bar');
    
        // Function to animate the bars
        function animateBars() {
            bars.forEach(bar => {
                const value = bar.getAttribute('data-value'); // Get the data-value attribute
                const maxValue = 35; // Set this to the maximum possible value for scaling
                const height = (value / maxValue) * 100; // Calculate the height as a percentage
    
                // Set the height dynamically
                bar.style.height = `${height}%`;
            });
        }
    
        // Call the function to start the animation
        animateBars();
    });
    