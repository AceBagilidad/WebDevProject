<?php
// session_start();
include('./config/dbconnection.php');


if (!isset($_SESSION['userId'])) {
    echo "<script>alert('Error: You must be logged in to access your cart.'); window.location.href = './loginPage.php'</script>";
    exit; // Stop further execution of the script
}

$user_id = $_SESSION['userId'];

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $user_id, $delete_id);
    if ($delete_stmt->execute()) {
        echo "<script>alert('Item removed from cart.'); window.location.href = 'cart.php';</script>";
    } else {
        echo "<script>alert('Failed to remove item. Please try again.');</script>";
    }
}

// Fetch cart items for the logged-in user
$cart_query = "SELECT c.product_id, c.quantity, p.name, p.price, p.image_url 
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

// Handle quantity increase
if (isset($_POST['increase'])) {
    $product_id = intval($_POST['product_id']);
    $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    header("Location: cart.php");
    exit;
}

// Handle quantity decrease
if (isset($_POST['decrease'])) {
    $product_id = intval($_POST['product_id']);
    $update_query = "UPDATE cart SET quantity = quantity - 1 WHERE product_id = ? AND quantity > 1";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    header("Location: cart.php");
    exit;
}


?>