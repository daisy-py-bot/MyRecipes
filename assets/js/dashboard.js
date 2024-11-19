
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JS loaded...');
});

// Function to animate the bars
function animateBars() {
    // Get all the bars
    const bars = document.querySelectorAll('.bar');
    bars.forEach(bar => {
        const value = bar.getAttribute('data-value'); // Get the data-value attribute
        const maxValue = 35; // Set this to the maximum possible value for scaling
        const height = (value / maxValue) * 100; // Calculate the height as a percentage

        // Set the height dynamically
        bar.style.height = `${height}%`;
    });
}

    
// Function to update dashboard stats
function updateDashboardStats() {
    console.log('Fetching dashboard stats...');
    // Fetch data from the server
    fetch('http://localhost/recipe_sharing/db/get_statistics.php')
        .then(response => response.json())
        .then(data => {
            // Check if the user is a super admin or regular admin and update accordingly
            console.log(data);
            // For Super Admin
            if (data.totalUsers !== undefined) {
                document.getElementById('num-all-users').textContent = data.totalUsers;
                document.getElementById('num-all-recipes').textContent = data.totalRecipes;
                document.getElementById('num-approved-recipes').textContent = data.totalApprovedRecipes;
            }

            // For Regular Admin
            else{
                document.getElementById('num-my-recipes').textContent = data.totalRecipes;
                document.getElementById('num-my-approved').textContent = data.totalApprovedRecipes;
            }
        })
        // .then(() => {
        //     animateBars();
        // })
        .catch(error => {
            console.error('Error fetching dashboard stats:', error);
        });

        // *** implement role based access here ***
        animateBars();

        
}

// Call the function when the page loads
window.onload = updateDashboardStats();
