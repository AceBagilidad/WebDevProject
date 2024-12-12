<?php 
include('./action/loginAction.php'); 
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./Design/loginPage.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <title>Sign In / Sign Up</title>

    </head>
    <body>
        <div class="container">
            <div class="welcome">
                <div class="welcome-text">
                    <h1>Welcome to LuXe</h1>
                    <p>Join us and explore amazing products just for you!</p>
                </div>

            </div>
            <div class="form-container">
                <h2 id="sign-in-header">Sign In</h2>
                <!-- Sign In Form -->
                <form id="sign-in-form" method="POST" action="loginPage.php">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="button">
                        <button type="submit" name="signin" class="button">Sign In</button>
                    </div>

                </form>

                <div class="toggle">
                    <p>Don't have an account? <a href="#sign-up" onclick="toggleForms()">Sign Up</a></p>
                </div>

                <!-- Sign Up Form -->
                <h2 id="sign-up-header" style="display:none;">Sign Up</h2>
                <form id="sign-up-form" method="POST" action="loginPage.php" style="display:none;">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                    <div class="button">
                    <button type="submit" name="signup" class="button">Sign Up</button>
                    </div>
                </form>

                <div class="toggle" style="display:none;" id="toggle-to-sign-in">
                    <p>Already have an account? <a href="#sign-in" onclick="toggleForms()">Sign In</a></p>
                </div>
            </div>
        </div>

        <script src="./Design/JavaScript/login.js">
        </script>

    </body>

    </html>