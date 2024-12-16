<?php
include("config/dbconnection.php");
?>

<section class="rectangle-header">
    <div class="lu-xe">
        <a href="./">
            <p class="lu-xe-text">
                Lu<span class="lu-xe-span">X</span>e
            </p>
        </a>
    </div>
    <div>
        <ul id="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="#about-section">About</a></li>
            <li class="nav-mobile"><a href="./cart.php">Cart</a></li>
            <?php if(isset($_SESSION['username'])) : ?>
            <li class="nav-mobile"><a href="./reservationPage.php">Reservations</a></li>
            <li class="nav-mobile"><a href="javascript:void(0);" onclick="logoutConfirm()">Log Out</a></li>
            <?php else : ?>
            <li class="nav-mobile"><a href="./loginPage.php">Log In</a></li>
            <?php endif; ?>
            <li id="nav-icon">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- User is logged in -->
                    <div class="dropdown">
                        <i class='bx bx-user icon'></i>
                        <div class="dropdown-content">
                            <a href="./reservationPage.php">Reservations</a>
                            <a href="javascript:void(0);" onclick="logoutConfirm()">Log Out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="./loginPage.php"> <i class='bx bx-user icon'></i></a>
                <?php endif; ?>
            </li>
            <li id="nav-icon">
                <a href="cart.php"> <i class='bx bx-cart icon'></i></a>
            </li>
            <a id="close"><i class="far fa-times"></i></a>
        </ul>
    </div>
    <div id="mobile">
        <!-- <a href="loginPage.php"> <i class='bx bx-user icon'></i></a> -->
        <!-- <a href="cart.php"> <i class='bx bx-cart icon'></i></a> -->
        <i id="bar" class="fas fa-outdent"></i>
    </div>
</section>

<script>
function logoutConfirm() {
    // Show the alert message
    if (confirm("You've been logged out successfully!")) {
        // Redirect to logout.php to handle session destruction
        window.location.href = "action/logout.php";
    }
}
</script>
