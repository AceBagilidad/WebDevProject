<?php
session_start();

include('./config/dbconnection.php');
// include('./action/reservation.php');s


if (!isset($_SESSION['userId'])) {
    echo "<script>alert('Error: You must be logged in to access your cart.'); window.location.href = './loginPage.php'</script>";
    exit; 
}

$user_id = $_SESSION['userId'];

$cart_query = "SELECT c.product_id, c.quantity, p.name, p.price, p.image_url 
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = $user_id";

$cart_result = $conn->query($cart_query);
$cart_products = $cart_result->fetch_all(MYSQLI_ASSOC);

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
    <title>Document</title>
</head>

<body class="product-page">
<?php include('./includes/header.php'); ?>

<section id="page-header">
    <div class="header-text">
        <h1>#cart</h1>
        <p>Invest in Memories, A Decision You'll Always Treasure</p>
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
    if (!empty($cart_products)) {
        foreach ($cart_products as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            ?>
            <tr>
                <td><a href="cart.php?delete_id=<?php echo $item['product_id']; ?>"><i class="far fa-times-circle"></i></a></td>
                <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Product Image"></td>
                <td>
                    <a href="sproduct.php?id=<?php echo $item['product_id']; ?>">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </a>
                </td>
                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                <td>
                    <form action="cart.php" method="POST" class="quantity-form">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="number" name="quantity"  value="<?php echo $item['quantity']; ?>" min="1" readonly>
                        <button type="submit" name="decrease" class="cart-qty-btn">-</button>
                        <button type="submit" name="increase" class="cart-qty-btn">+</button>
                    </form>
                </td>
                <td>₱<?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='6'>Your cart is empty.</td></tr>";
    }
    ?>
</tbody>

    </table>
</section>

<section id="cart-add" class="section-p1">
    <div class="cart-addSec">
        <div id="subtotal">
            <h3>Cart Total</h3>
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td>₱<?php echo number_format($total, 2); ?></td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td>Free</td>   
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>₱<?php echo number_format($total, 2); ?></strong></td>
                </tr>
            </table>
            <form action="./action/reservation.php" method="POST">
                <button type="submit" name="reserve" class="button">Add to Reservation</button>
            </form>
        </div>
    </div>
</section>

<?php include('./includes/footer.php'); ?>
</body>
<script src="./Design/JavaScript/hamburger.js"></script>
</html>