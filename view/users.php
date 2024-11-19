<!-- This is only accessed by the users -->
<?php
    session_start();

    // check if the user is logged in

    if(!isset($_SESSION['user_id'])){
        header("Location: ../login.html");
    }


    $role =(int) $_SESSION['role'];

    // check if the user is an admin
    if( $role !== 1){
        echo "No access to the dashboard";
        header("Location: index.html");
        exit();
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/users.css">
    <link rel="stylesheet" href="../assets/css/global_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="sidebar">
        <a href="#" class="menu-logo" >
            <img src="../assets/images/mylogo.png" alt="logo">
         </a>
        <h2>Admin Panel</h2>
        <p>
            <?php 
            // echo $_SESSION['fname'] . " " . $_SESSION['lname']  . " role: " . $role;?>
        </p>
        <ul>

            <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <!-- disable the access to the users page if the user is not a super admin -->
            <?php if($role === 1): ?>
            <li><a href="users.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <?php endif; ?>

            <li><a href="recipes.php"><i class="fas fa-utensils"></i><span>Recipes</span></a></li>
            <li><a href="#"><i class="fas fa-cog"></i><span>Settings</span></a></li>
            <li><a href="index.html" target="_blank"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li><a href="logout.html"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>


    <div  class="main-content">
        <!-- User header -->

        <div class="header">
            <h1>Dashboard</h1>
            <p>View the stats. Manage your users</p>
        </div>

       


        <div class="users-container">
    
            <!-- User Table -->
            <table id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- User rows will be dynamically generated here (from js)-->
                </tbody>
            </table>

    
            <!-- Edit User Modal -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModal">&times;</span>
                    <h2>Edit User</h2>
                    <form id="editForm">
                        <input type="hidden" id="editUserId">
                        <label for="editUserName">Name:</label>
                        <span id="username-error-message" class="error"></span>
                        <input type="text" id="editUserName" required>
                        <label for="editUserEmail">Email:</label>
                        <input type="email" id="editUserEmail" required>
                        <span id="email-error-message" class="error"></span>
                        <button type="submit">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    
        
    </div>
    <script src="../assets/js/users.js"></script>
    <script src="../assets/js/get_users.js"></script>
</body>

</html>



