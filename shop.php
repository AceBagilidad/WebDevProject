<?php
session_start();
// Get the logged-in username
$username = '';
if(isset($_SESSION['username'])) {
$username = htmlspecialchars($_SESSION['username']);
}

$category = '';

if(isset($_GET['category'])) {
    $category = $_GET['category'];
}

include("config/dbconnection.php");
// Fetch products from the database
$sql = "
    SELECT p.id, p.name, p.price, p.image_url, p.category,
    COALESCE(AVG(r.rating), 0) AS rating
    FROM products p
    LEFT JOIN reviews r ON p.id = r.product_id
    GROUP BY p.id
";
$result = $conn->query($sql);
$products = $result->fetch_all(MYSQLI_ASSOC);

$filters = [
    '' => 'All',
    'choker' => 'Choker',
    'necklace' => 'Necklace',
    'bracelet' => 'Bracelet',
    'earring' => 'Earrings',
    'anklet' => 'Anklet'
];

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
    <link href='https://unpkg.com/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Shop</title>
</head>

<body class="product-page">
<?php include('./includes/header.php')?>
    <section id="page-header">
        <div class="header-text">
        <h1>Welcome <?php echo $username; ?>!</h1>
            <p>Explore our premium products, where quality meets affordability </p>
        </div>
    </section>


    <section id="prod1" class="section-p1">


        <h1>Featured Products</h1>
        <p>Exclusive Accessories New Modern Design </p>

        <div class="search-filter-container">


        <!-- Filter section -->
        <div class="filter">
            <i class="fal fa-filter"></i>
            <span>Filter by:</span>
            <select id="categoryFilter">
                <?php foreach($filters as $value => $label): ?>
                    <option value="<?php echo $value; ?>" <?php echo $value === $category ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>


            <select id="sortFilter">
                <option value="">Sort by</option>
                <option value="a-z">A-Z</option>
                <option value="price-low-high">Price: Low to High</option>
                <option value="price-high-low">Price: High to Low</option>
                <option value="popularity">Popularity</option>
            </select>
        </div>
    </div>
    <div class="prod-container">
    <?php

        if (count($products) > 0) {
            foreach($products as $product) {
                $rating = round($product['rating']);  // Round the rating to the nearest integer
                echo '<div class="prod" data-id="' . $product['id'] . '"
                        data-category="' . htmlspecialchars($product['category']) . '"
                            data-name="' . htmlspecialchars($product['name']) . '"
                            data-price="' . htmlspecialchars($product['price']) . '"
                            data-rating="' . htmlspecialchars($product['rating']) . '">
                            <img src="' . htmlspecialchars($product['image_url']) . '" alt="">
                            <div class="des">
                                <span>LuXe</span>
                                <h2>' . htmlspecialchars($product['name']) . '</h2>
                                <div class="star">';

                for ($i = 0; $i < 5; $i++) {
                    echo $i < $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                }

                echo '</div>
                        <h3>₱' . number_format($product['price'], 2) . '</h3>
                        </div>
                    // </a>
                    // <a href="#"><i class="fal fa-cart-plus cart"></i></a>
                    // </div>';
            }
        } else {
            echo "<p>No products available.</p>";
        }
    ?>
</div>


    </section>

    <?php include('./includes/footer.php')?>
    <script src="./Design/JavaScript/filterProd.js"></script>
    <script src="./Design/JavaScript/hamburger.js"></script>

</body>
</html>