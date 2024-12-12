<?php
include("../config/dbconnection.php");

// Get the selected category from the query string
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Base SQL query
// Base SQL query to select product details and calculate average rating from the reviews table
$sql = "SELECT p.name, p.price, p.image_url, p.category, 
                IFNULL(AVG(r.rating), 0) AS rating  // Use IFNULL to handle products with no reviews
        FROM products p
        LEFT JOIN reviews r ON p.id = r.product_id";  // Join reviews table on product_id

// If a category is selected, add a WHERE clause
if (!empty($category)) {
    $sql .= " WHERE category = ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($category)) {
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch all products
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return the products as JSON
header('Content-Type: application/json');
echo json_encode($products);






?>
