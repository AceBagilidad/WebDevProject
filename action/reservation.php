<?php
session_start();

include('../config/dbconnection.php');



$user_id = $_SESSION['userId'];

// Handle reservation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {
    // --- Get all cart items for the current user ---
    $cart_query = "SELECT product_id, quantity FROM cart WHERE user_id = $user_id";
    $cart_result = $conn->query($cart_query);
    $cart_products = $cart_result->fetch_all(MYSQLI_ASSOC);
    var_dump($cart_products);

    if ($cart_result->num_rows > 0) {
        $insert_success = true;
        $insert_stmt = $conn->prepare("INSERT INTO reservations (user_id, product_id, quantity) VALUES (?, ?, ?)");
        
        foreach ($cart_products as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            
            $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
            
            if (!$insert_stmt->execute()) {
                var_dump($user_id, $product_id, $quantity);
                $insert_success = false;
                exit;
                break; // Stop further inserts if one fails
            }
        }
        
        $insert_stmt->close();
        


        if ($insert_success) {
            // --- Clear user's cart if all inserts are successful ---
            $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";
            $delete_cart_stmt = $conn->prepare($delete_cart_query);
            $delete_cart_stmt->bind_param("i", $user_id);
            if ($delete_cart_stmt->execute()) {
                header("Location: reservationPage.php");
                exit;
            } else {
                echo "<script>alert('Failed to clear cart after reservation.');</script>";
            }
            $delete_cart_stmt->close();
        } else {
            echo "<script>alert('Failed to process reservation. Please try again.');</script>";
            header("Location: ../cart.php");
            exit;
        }
    } else {
        echo "<script>alert('Your cart is empty.'); window.history.back();</script>";
    }
}
?>
