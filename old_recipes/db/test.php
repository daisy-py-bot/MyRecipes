

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/global_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>
<body>

    <div class="sidebar">
        <a href="#" class="menu-logo" >
            <img src="../media/mylogo.png" alt="logo">
         </a>
        <h2>Admin Panel</h2>
        <ul>

            <li><a href="dashaboard.html"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li><a href="users.html"><i class="fas fa-users"></i><span>Users</span></a></li>
            <li><a href="recipes.html"><i class="fas fa-utensils"></i><span>Recipes</span></a></li>
            <li><a href="#"><i class="fas fa-cog"></i><span>Settings</span></a></li>
            <li><a href="index.html" target="_blank"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li><a href="logout.html"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>
    
</body>
</html>



<?php
// session_start();
// include 'config.php';

// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Super Admin') {
//     header('Location: login.php'); 
//     exit();
// }
echo "Hello from dashboard";
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js library -->
</head>
<body>

<div class="dashboard">
    <h1>Super Admin Dashboard</h1>

    <!-- Analytics Section -->
    <?php
    $totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
    $totalRecipes = $conn->query("SELECT COUNT(*) AS count FROM recipes")->fetch_assoc()['count'];
    $pendingApprovals = $conn->query("SELECT COUNT(*) AS count FROM users WHERE status = 'pending'")->fetch_assoc()['count'];
    ?>

    <div class="analytics-section">
        <h2>Analytics</h2>
        <ul>
            <li>Total Users: <?php echo $totalUsers; ?></li>
            <li>Total Recipes: <?php echo $totalRecipes; ?></li>
            <li>Pending Approvals: <?php echo $pendingApprovals; ?></li>
        </ul>
    </div>

    <!-- User Management Section -->
    <?php
    $users = $conn->query("SELECT user_id, fname, lname, email, role, status, registration_date FROM users")->fetch_all(MYSQLI_ASSOC);
    ?>

    <div class="user-management-section">
        <h2>User Management</h2>
        <table>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Registration Date</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo htmlspecialchars($user['status']); ?></td>
                <td><?php echo htmlspecialchars($user['registration_date']); ?></td>
                <td>
                    <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Handle Delete User Action -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
        $userId = $_POST['delete_user_id'];
        $deleteStmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $deleteStmt->bind_param("i", $userId);
        $deleteStmt->execute();
        header('Location: dashboard.php'); // Refresh the page to show the updated user list
        exit();
    }
    ?>

    <!-- Recipe Overview Section -->
    <?php
    $recentRecipes = $conn->query("SELECT title, created_at FROM recipes ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
    ?>

    <div class="recipe-overview-section">
        <h2>Recipe Overview</h2>
        <p>Total Recipes: <?php echo $totalRecipes; ?></p>
        <h3>Recently Added Recipes</h3>
        <ul>
            <?php foreach ($recentRecipes as $recipe): ?>
            <li><?php echo htmlspecialchars($recipe['title']) . " - " . $recipe['created_at']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Optional: Analytics Charts -->
    <div class="analytics-charts">
        <h2>Analytics Charts</h2>
        <canvas id="userRegistrationChart"></canvas>
        <canvas id="recipeSubmissionChart"></canvas>
        <canvas id="userApprovalStatusChart"></canvas>
    </div>

    <script>
        // Example data for charts - replace with actual data from PHP if needed
        const userRegistrationData = [/* registration counts here */];
        const recipeSubmissionData = [/* submission counts here */];
        const userApprovalData = [/* approval status counts here */];

        // User Registration Chart
        const ctx1 = document.getElementById('userRegistrationChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar'], // Example labels
                datasets: [{
                    label: 'User Registrations',
                    data: userRegistrationData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        // Recipe Submission Chart
        const ctx2 = document.getElementById('recipeSubmissionChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar'], // Example labels
                datasets: [{
                    label: 'Recipe Submissions',
                    data: recipeSubmissionData,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        // User Approval Status Chart
        const ctx3 = document.getElementById('userApprovalStatusChart').getContext('2d');
        new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Pending', 'Rejected'], // Example labels
                datasets: [{
                    label: 'User Approval Status',
                    data: userApprovalData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });
    </script>
</div>
</body>
</html>
