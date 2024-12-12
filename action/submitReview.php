<?php
session_start();
var_dump('product_id');
include("../config/dbconnection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in using the correct session key
    if (!isset($_SESSION['userId'])) {
        echo "<script>alert('Error: You must be logged in to submit a review.'); window.location.href = '../loginPage.php'</script>";
        exit;
    }

    $user_id = $_SESSION['userId']; // Updated key
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Validate inputs
    if (!$product_id || !$rating || !$comment) {
        echo "<script>alert('Error: All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Insert the review into the database
    $query = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo "<script>alert('Error: Prepare failed: " . $conn->error . "'); window.history.back();</script>";
        exit;
    }

    if (!$stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment)) {
        echo "<script>alert('Error: Bind param failed: " . $stmt->error . "'); window.history.back();</script>";
        exit;
    }

    if (!$stmt->execute()) {
        echo "<script>alert('Error: Execute failed: " . $stmt->error . "'); window.history.back();</script>";
        exit;
    }

    $stmt->close();
    echo "<script>alert('Review submitted successfully.'); window.location.href = '../sproduct.php?id=" . $product_id . "';</script>";
    exit;
}
