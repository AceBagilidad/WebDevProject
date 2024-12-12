<?php
session_start();

include('./config/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo "<script>alert('Error: You must be logged in to access your reservations.'); window.location.href = './loginPage.php';</script>";
    exit; 
}

$user_id = $_SESSION['userId'];

// delete reservation
if(isset($_GET['delete_id'])) {
    $product_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM reservations WHERE user_id = $user_id AND product_id = $product_id";

    if ($conn->query($delete_query)) {
        header("Location: ./reservationPage.php");
    } else {
        echo "Error deleting reservation: " . $conn->error;
    }

    exit;
}

$reservation_query = "
    SELECT r.product_id, r.quantity, p.name, p.price, p.image_url 
    FROM reservations r
    JOIN products p ON r.product_id = p.id
    WHERE r.user_id = $user_id
";

$reservation_result = $conn->query($reservation_query);
$reservation_products = $reservation_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=#, initial-scale=1.0">
    <link rel="stylesheet" href="./Design/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href='https://unpkg.com/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Reservation Page</title>
</head>

<body class="product-page">
    <?php include('./includes/header.php'); ?>

    <section id="page-header">
        <div class="header-text">
            <h1>#reservation</h1>
        </div>
    </section>

    <section id="cart" class="section-p1">
        <table width="100%">
            <thead>
                <tr>
                    <td>Remove</td>
                    <td>Image</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Subtotal</td>
                </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            if ($reservation_result->num_rows > 0) {
                foreach ($reservation_products as $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
            ?>
                <tr>
                    <td><a href="./reservationPage.php?delete_id=<?php echo $item['product_id']; ?>"><i class="far fa-times-circle"></i></a></td>
                    <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Product Image"></td>
                    <td>
                        <a href="sproduct.php?id=<?php echo $item['product_id']; ?>">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </a>
                    </td>
                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($subtotal, 2); ?></td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6'>Your reservation is empty.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </section>

    <?php include('./includes/footer.php'); ?>
</body>
<script src="./Design/JavaScript/hamburger.js"></script>
</html>
