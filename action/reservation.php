<?php
session_start();

include('../config/dbconnection.php');

$user_id = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve'])) {

    $cart_query = "SELECT product_id, quantity FROM cart WHERE user_id = $user_id";
    $cart_result = $conn->query($cart_query);

    if ($cart_result->num_rows > 0) {

        $cart_products = $cart_result->fetch_all(MYSQLI_ASSOC);

        foreach ($cart_products as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $insert_query = "INSERT INTO reservations (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
            if(!$conn->query($insert_query)) {
                echo $conn->error;
                exit;
            }
        }

        $delete_cart_query = "DELETE FROM cart WHERE user_id = $user_id";
        
        if ($conn->query($delete_cart_query)) {
            header("Location: ../reservationPage.php");
            exit;
        }
    
    } else {
        echo "<script>alert('Your cart is empty.');</script>";
        header("Location: ../cart.php");
        exit;
    }
}
?>
