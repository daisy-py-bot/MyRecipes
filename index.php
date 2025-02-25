<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/global_user.css">
</head>
<body>

    <header>
        <nav class="menu-container">
            <!-- burger menu -->
            <input type="checkbox" aria-label="Toggle menu" />
            <span></span>
            <span></span>
            <span></span>
          
            <!-- logo -->
            <a href="#" class="menu-logo">
               <img src="assets/images/mylogo.png" alt="logo">
            </a>
          
            <!-- menu items -->
            <div class="menu">
              <ul>
                <li>
                  <a href="#home">
                    Home
                  </a>
                </li>
                <li>
                  <a href="view/recipe_feed.html">
                    Recipes
                  </a>
                </li>
                <li>
                  <a href="#favorites">
                    Favorites
                  </a>
                </li>
                <li>
                  <a href="view/admin/dashboard.php">
                    Dashboard
                  </a>
                </li>
              </ul>
              <ul>
                <li>
                  <a href="view/register_user.html">
                    Sign-up
                  </a>
                </li>
                <li>
                  <a href="view/login.html">
                    Login
                  </a>
                </li>
              </ul>
            </div>
          </nav>
    </header>
    
    <main>
        <div class="introMsg">
            <h1>Welcome to <span style="font-weight: bolder; font-style: italic; font-size: 70px; color: orange;">MyRecipes</span></h1>
            <div class="welcome" >
                <p>
                    Join <span style="font-weight: bolder; font-style: italic; font-size: 30px; color: orange;">MyRecipes</span> 
                    today and get access to a miilion of delicious delicacies
                </p>
            </div>
           
            <a href="recipe_feed.html">
                <div class="explore">
                    <p>Start exploring</p>
                    <!-- <img src="../media/arrow.png" alt="left arrow"> --> 
                </div>
            </a>
            
        </div>

    </main>
</body>
</html>