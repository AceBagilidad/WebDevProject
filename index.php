<?php
session_start();

include("./config/dbconnection.php");

$username = '';

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$popular_products_query = "
    SELECT p.*, AVG(r.rating) AS avg_rating
    FROM products p
    LEFT JOIN reviews r ON p.id = r.product_id
    GROUP BY p.id
    HAVING avg_rating > 2  
    ORDER BY avg_rating DESC";

$popular_products_result = $conn->query($popular_products_query);
$popular_products = $popular_products_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Design/HomePage.css">
    <link href='https://unpkg.com/boxicons@latest/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Home Page</title>
</head>
<body>
    <header>
        <div class="rectangle-header">
        <div class="lu-xe">
            <a href="#home">
                <p class="lu-xe-text">
                    Lu<span class="lu-xe-span">X</span>e
                </p>
            </a>
        </div>
        <div class="navigation">
            <ul>
                <li class="nav-item">
                    <a class="active" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a href="shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a href="#about-section">About</a>
                </li>
            </ul>
        </div>
        <div class="icons-button">
            <?php if ($username): ?>
                <!-- User is logged in -->
                <div class="dropdown">
                    <i class='bx bx-user icon'></i>
                    <div class="dropdown-content">
                        <a href="action/logout.php">Log Out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="loginPage.php"><i class='bx bx-user'></i></a>
            <?php endif; ?>
            <a href="cart.php"><i class='bx bx-cart'></i></a>
        </div>
        </div>
    </header>
    <main id="home">
        <div class="hero-section">
            <div class="hero-text">
                <h1>Welcome to LuXe</h1>
                <p>
                    LuXe is an innovative fashion brand that offers stylish, high-quality accessories designed for everyone. We believe in creating pieces that embody elegance and comfort, all while being environmentally conscious.
                </p>
            </div>
        </div>
        <div class="section-two">
            <div class="section-two-text">
                <h2>LuXe</h2>
                <p>
                    Indulge in accessible luxury that speaks to <br> your style.
                </p>
            </div>
            <img src="./Image/body-2.png">
        </div>
        <div class="section-three">
            <div class="slide">
                <div class="item" style="background-image: url('./Image/img1.png');">
                    <div class="content">
                        <div class="name">The LuXe Bracelet</div>
                        <div class="des">A seamless blend of style and sophistication, the LuXe Bracelet is designed to enhance any outfit with its refined elegance. Perfect for daily wear, this bracelet adds a touch of luxury that effortlessly elevates your look, making it an essential accessory for every occasion.</div>
                        <!-- Pass 'bracelets' as the category in the query string -->
                        <a href="shop.php?category=bracelets"><button>Shop Now</button> </a>
                    </div>
                </div>
                <div class="item" style="background-image: url('./Image/img2.png');">
                    <div class="content">
                        <div class="name">The LuXe Anklet</div>
                        <div class="des">A subtle statement of elegance for your everyday look. This anklet adds a hint of sophistication and charm, designed to be both timeless and versatile for any occasion.</div>
                        <!-- Pass 'anklets' as the category in the query string -->
                        <a href="shop.php?category=anklets"><button>Shop Now</button> </a>
                    </div>
                </div>
                <div class="item" style="background-image: url('./Image/Choker/black-leather-choker-necklace.jpg');">
                    <div class="content">
                        <div class="name">The LuXe Choker</div>
                        <div class="des">Bold and refined, the LuXe Choker adds an edge to any outfit. Crafted for comfort and style, it's the perfect blend of modern elegance and statement-making design.</div>
                        <!-- Pass 'chokers' as the category in the query string -->
                        <a href="shop.php?category=chokers"><button>Shop Now</button> </a>
                    </div>
                </div>
                <div class="item" style="background-image: url('./Image/img4.png');">
                    <div class="content">
                        <div class="name">The LuXe Necklace</div>
                        <div class="des">A timeless symbol of grace, the LuXe Necklace is designed to enhance your look with effortless sophistication. Crafted with care, it's a versatile piece that brings luxury to any occasion.</div>
                        <!-- Pass 'necklaces' as the category in the query string -->
                        <a href="shop.php?category=necklaces"><button>Shop Now</button> </a>
                    </div>
                </div>
                <div class="item" style="background-image: url('./Image/img5.png');">
                    <div class="content">
                        <div class="name">The LuXe Earrings</div>
                        <div class="des">A touch of elegance in every detail. These earrings are designed to effortlessly elevate your style, offering a perfect blend of luxury and everyday wearability.</div>
                        <!-- Pass 'earrings' as the category in the query string -->
                        <a href="shop.php?category=earrings"><button>Shop Now</button> </a>
                    </div>
                </div>
            </div>
            <div class="button">
                <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
                <script src="./Design/JavaScript/carousel.js"></script>
            </div>
        </div>
    </main>

    <section id="prod1" class="section-p1">
    <h1>Popular Products</h1>
    <p>Exclusive Accessories New Modern Design</p>

    <div class="prod-container">
    <?php
    if (!empty($popular_products)) {
        foreach ($popular_products as $product) {
            $avg_rating = round($product['avg_rating']);  
            echo '
            <div class="prod" data-id="' . $product['id'] . '" 
                data-category="' . htmlspecialchars($product['category']) . '" 
                data-name="' . htmlspecialchars($product['name']) . '" 
                data-price="' . htmlspecialchars($product['price']) . '" 
                data-rating="' . htmlspecialchars($product['avg_rating']) . '">
                <a href="sproduct.php?id=' . $product['id'] . '"> 
                <img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">
                <div class="des">
                <span>LuXe</span>
                <h2>' . htmlspecialchars($product['name']) . '</h2>
                <div class="star">';
            
            // Display the stars based on the average rating
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $avg_rating) {
                    echo '<i class="fas fa-star"></i>';  // Full star
                } else {
                    echo '<i class="far fa-star"></i>';  // Empty star
                }
            }
            echo '
                        </div>
                        <h3>₱' . number_format($product['price'], 2) . '</h3>
                    </div>
                </a>
            </div>';
        }
    } else {
    echo "<p>No popular products available.</p>";
    }
    ?>
    </div>
    </section>
    <?php include('./includes/footer.php')?>
    <script src="./Design/JavaScript/HomePageDesign.js"></script>
</body>
</html>