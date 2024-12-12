<?php
    session_start();
    include("config/dbconnection.php");

// Sign Up Handling
if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful!'); window.location.href='loginPage.php';</script>";
        } else {
            echo "<script>alert('Registration Failed! Email or Username already exist');</script>";
        }
        $stmt->close();
    }
}

// Sign In Handling
if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id, username, password, is_admin FROM users WHERE email = ?');
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $username, $hashedPassword, $isAdmin);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Store user info in session
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['isAdmin'] = $isAdmin;  // Store the is_admin status in session

            // Redirect based on whether the user is an admin or not
            if ($isAdmin) {
                // If admin, redirect to the addProduct_Page.php
                header("Location: addProduct_Page.php");
            } else {
                // If regular user, redirect to the shop.php
                header("Location: shop.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid credentials!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.');</script>";
    }
    $stmt->close();
}


 // Close connection
 if (isset($conn)) {
    $conn->close();
}
?>