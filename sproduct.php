<?php
session_start();
include './config/dbconnection.php';

// Get the product_id from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database
$product_query = "SELECT * FROM products WHERE id = ?";
$product_stmt = $conn->prepare($product_query);
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();

if ($product_result->num_rows > 0) {
    $product = $product_result->fetch_assoc();
} else {
    echo "<p>Product not found.</p>";
    exit;
}

// Fetch product reviews from the database
$reviews_query = "SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC";
$reviews_stmt = $conn->prepare($reviews_query);
$reviews_stmt->bind_param("i", $product_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();

// Get related products
$category = $product['category'];
$related_query = "
    SELECT p.*, AVG(r.rating) AS avg_rating
    FROM products p
    LEFT JOIN reviews r ON p.id = r.product_id
    WHERE p.category = ? AND p.id != ?
    GROUP BY p.id
    ORDER BY avg_rating DESC
    LIMIT 4
";

$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param("si", $category, $product_id); // Pass both parameters
$related_stmt->execute();
$related_result = $related_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Design/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href='https://unpkg.com/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title><?php echo htmlspecialchars($product['name'] ?? ''); ?></title>
</head>

<body class="product-page">
  <?php include('./includes/header.php')?>

    <section id="prod-details" class="section-p1">
        <div class="sprod-image">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>" width="100%" id="mainImg" alt="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
        </div>

        <div class="sprod-details">
            <h6><?php echo htmlspecialchars($product['category'] ?? ''); ?></h6>
            <h4><?php echo htmlspecialchars($product['name'] ?? ''); ?></h4>
            <h2>₱<?php echo number_format($product['price'], 2); ?></h2>

            <form action="./action/addCart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit" class="button">Add to Cart</button>
            </form>

            <h6 class="product-details-title">Product Details</h6>
            <p class="product-description">
                <?php echo htmlspecialchars($product['description'] ?? ''); ?>
            </p>

            <!-- Reviews Section -->
            <div class="review-section">
                <h6>Customer Reviews & Rating</h6>

                <!-- Display average rating -->
                <div class="average-rating">
                    <?php
                    // Calculate average rating
                    $average_rating_query = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = ?";
                    $average_rating_stmt = $conn->prepare($average_rating_query);
                    $average_rating_stmt->bind_param("i", $product['id']);
                    $average_rating_stmt->execute();
                    $average_rating_result = $average_rating_stmt->get_result();
                    $average_rating = $average_rating_result->fetch_assoc()['avg_rating'];
                    $average_rating = $average_rating !== null ? round($average_rating, 1) : 0; // Default to 0 if null
                    ?>
                    <span>Average Rating:</span>
                    <div class="rating">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < $average_rating ? '<span class="star">&#9733;</span>' : '<span class="star">&#9734;</span>';
                        }
                        ?>
                        <span>(<?php echo $average_rating; ?>)</span>
                    </div>
                </div>

                <div class="review-form">
                    <span>Rate this product:</span>
                    <div class="rating-input">
                        <form action="./action/submitReview.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <div class="rating-input">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" title="1 star">★</label>
                            </div>
                            <textarea name="comment" placeholder="Leave your comment here..." rows="4" required></textarea>
                            <button class="button submit-comment">Submit Review</button>
                        </form>
                    </div>
                </div>

                <!-- Display existing comments and ratings -->
                <div class="review-comments">
                    <h6>Top Customer Reviews</h6>
                    <?php
                    $reviews_query = "SELECT r.rating, r.comment, r.created_at, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? LIMIT 5";
                    $reviews_stmt = $conn->prepare($reviews_query);
                    $reviews_stmt->bind_param("i", $product['id']);
                    $reviews_stmt->execute();
                    $reviews_result = $reviews_stmt->get_result();

                    if ($reviews_result->num_rows > 0) {
                        while ($review = $reviews_result->fetch_assoc()) { ?>
                            <div class="review">
                                <strong><?php echo htmlspecialchars($review['username'] ?? ''); ?></strong>
                                <span class="review-date"> - <?php echo htmlspecialchars(date("F j, Y", strtotime($review['created_at']))); ?></span>
                                <div class="rating">
                                    <?php
                                    for ($i = 0; $i < 5; $i++) {
                                        echo $i < $review['rating'] ? '<span class="star">&#9733;</span>' : '<span class="star">&#9734;</span>';
                                    }
                                    ?>
                                </div>
                                <p><?php echo htmlspecialchars($review['comment'] ?? ''); ?></p>
                            </div>
                        <?php }
                    } else {
                        echo "<p>No reviews yet. Be the first to review!</p>";
                    }
                    ?>
                </div>

            </div>
        </div>

    </section>

    <section id="prod1" class="section-p1">
        <h1>Related Products</h1>
        <p>Exclusive Accessories New Modern Design </p>

        <div class="prod-container">
            <?php
            if ($related_result->num_rows > 0) {
                while ($related_product = $related_result->fetch_assoc()) {
                    $avg_rating = round($related_product['avg_rating'] ?? 0); // Ensure avg_rating is a number, default to 0 if null
                    echo '
                    <div class="prod" data-id="' . $related_product['id'] . '" 
                        data-category="' . htmlspecialchars($related_product['category'] ?? '') . '" 
                        data-name="' . htmlspecialchars($related_product['name'] ?? '') . '" 
                        data-price="' . htmlspecialchars($related_product['price'] ?? 0) . '" 
                        data-rating="' . htmlspecialchars($related_product['avg_rating'] ?? 0) . '">
                        <a href="sproduct.php?id=' . $related_product['id'] . '&category=' . urlencode($related_product['category'] ?? '') . '">

                            <img src="' . htmlspecialchars($related_product['image_url'] ?? '') . '" alt="' . htmlspecialchars($related_product['name'] ?? '') . '">
                            <div class="des">
                                <span>LuXe</span>
                                <h2>' . htmlspecialchars($related_product['name'] ?? '') . '</h2>
                                <div class="star">';

                    // Render stars based on average rating
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $avg_rating) {
                            echo '<i class="fas fa-star"></i>'; // Full star
                        } else {
                            echo '<i class="far fa-star"></i>'; // Empty star
                        }
                    }

                    echo '
                                </div>
                                <h3>₱' . number_format($related_product['price'], 2) . '</h3>
                            </div>
                        </a>
                    </div>';
                }
            } else {
                echo "<p>No related products available.</p>";
            }
            ?>
        </div>
    </section>

    <?php include('./includes/footer.php') ?>
</body>

</html>
