<?php
    // include_once '../../db/config.php';
    // opcache_reset();

    session_start();


    // Check if the user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {

        // redirect the user to the login page if they are not logged in
        header("Location: ../login.html");
        exit();
    }

    $role = (int)$_SESSION['role'];
    // echo "User role: ". $role . "<br>";

    // $role = 1;  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/global_admin.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>


    <div class="sidebar">
        <a href="#" class="menu-logo" >
            <img src="../../assets/images/mylogo.png" alt="logo">
         </a>
        <h2>Admin Panel</h2>
        <ul>

            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <!-- disable the access to the users page if the user is not a super admin -->
            <?php if($role === 1): ?>
            <li><a href="../users.php"><i class="fas fa-users"></i><span>Users</span></a></li>
            <?php endif; ?>
            
            
            <!-- <li><a href="../users.php"><i class="fas fa-users"></i><span>Users</span></a></li> -->
            <li><a href="../recipes.php"><i class="fas fa-utensils"></i><span>Recipes</span></a></li>
            <li><a href="#"><i class="fas fa-cog"></i><span>Settings</span></a></li>
            <li><a href="../../index.php" target="_blank"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li><a href="../logout.html"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>



    <div class="main-content">

        <div class="header">
            <h1>Dashboard</h1>
            <p>View the stats. Manage your users</p>
        </div>

        <!-- Dashboard analytics -->
        <div class="dashboard-grid">


        <!-- This information is seen by the super admin -->
        <?php if($role === 1): ?>
            <!-- Box 1: Number of Users -->
            <div class="box box-users">
                <h3>Total number of Users</h3>
                <p class="count" id="num-all-users" >0</p> 

            </div>

            <!-- Box 2: Number of Recipes for all users-->
            <div class="box box-users">
                <h3>Total number of Recipes</h3>
                <p class="count" id="num-all-recipes">0</p> 
            </div>

            <!-- Box 2: Number of approved recipes for all users -->
            <div class="box box-recipes">
                <h3>Total Number of Recipes Approved</h3>
                <p class="count" id="num-approved-recipes">0</p> 
            </div>
        

            <!-- This is seen by the regular admin -->
            <?php elseif($role === 2): ?>
            <!-- Box 2: Number of Recipes submitted by regular admin-->
            <div class="box box-recipes">
                <h3>My Recipes submitted</h3>
                <p class="count" id="num-my-recipes">0</p> 
            </div>


            <!-- Box 2: Number of Recipes approved by super admin for regular admin-->
            <div class="box box-recipes">
                <h3>My recipes approved</h3>
                <p class="count" id="num-my-approved">0</p> 
            </div>

            <?php endif; ?>
        


            <!-- This is seen by everyone -->
            <!-- Box 3: Recipes per Month (Graph) -->
            <div class="box box-graph">
                <h3>Recipes Per Month</h3>
                <div class="bar-chart">
                    <div class="bar" data-month="January" data-value="10"></div>
                    <div class="bar" data-month="February" data-value="15"></div>
                    <div class="bar" data-month="March" data-value="12"></div>
                    <div class="bar" data-month="April" data-value="9"></div>
                    <div class="bar" data-month="May" data-value="25"></div>
                    <div class="bar" data-month="June" data-value="30"></div>
                    <div class="bar" data-month="July" data-value="14"></div>
                    <div class="bar" data-month="August" data-value="18"></div>
                    <div class="bar" data-month="September" data-value="24"></div>
                    <div class="bar" data-month="October" data-value="22"></div>
                    <div class="bar" data-month="November" data-value="28"></div>
                    <div class="bar" data-month="December" data-value="35"></div>
                </div>
            </div>
            

        


            <!-- Box 4: Top 5 Users -->
            <div class="box box-top-users">
                <h3>Top 5 Users</h3>
                <div class="user-profile">
                    <img src="../../assets/images/dktpy.jpeg" alt="Profile Picture">
                    <div>
                        <a href="user2-profile.html" class="user-link">
                            <p class="full-name">Ruva Sadya</p>
                            <p class="username">@ruva01</p>
                        </a>
                    </div>
                </div>
                
                <div class="user-profile">
                    <img src="../../assets/images/ruva.jpg" alt="Profile Picture">
                    <div>
                        <a href="user2-profile.html" class="user-link">
                            <p class="full-name">Daisy Tsenesa</p>
                            <p class="username">@dktpy</p>
                        </a>
                    </div>
                </div>


                <div class="user-profile">
                    <img src="../../assets/images/ruva.jpg" alt="Profile Picture">
                    <div>
                        <a href="user2-profile.html" class="user-link">
                            <p class="full-name">Ruva Sadya</p>
                            <p class="username">@ruva01</p>
                        </a>
                    </div>
                </div>
                
                <div class="user-profile">
                    <img src="../../assets/images/dktpy.jpeg" alt="Profile Picture">
                    <div>
                        <a href="user2-profile.html" class="user-link">
                            <p class="full-name">Daisy Tsenesa</p>
                            <p class="username">@dktpy</p>
                        </a>
                    </div>
                </div>

                <div class="user-profile">
                    <img src="../../assets/images/dktpy.jpeg" alt="Profile Picture">
                    <div>
                        <a href="user2-profile.html" class="user-link">
                            <p class="full-name">Daisy Tsenesa</p>
                            <p class="username">@dktpy</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        


    </div>

    <script src="../../assets/js/dashboard.js"></script>
</body>
</html>

