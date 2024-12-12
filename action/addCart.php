<!-- Adding to cart from sproduct -->
<?php
session_start();
include '../config/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in using the correct session key
    if (!isset($_SESSION['userId'])) {
        echo "<script>alert('Error: You must be logged in to submit a review.'); window.location.href = '../loginPage.php'</script>";
        exit;
    }
}

$user_id = $_SESSION['userId'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Check if the item already exists in the user's cart
$query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If the item is already in the cart, update the quantity
    $update_query = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $update_stmt->execute();
} else {
    // If the item is not in the cart, insert a new row
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insert_stmt->execute();
}

echo "<script>
    alert('Item added to cart successfully.');
    window.location.href = '../cart.php';
</script>";
?>
