<?php 
    include('config/dbconnection.php');
    
    $query = "
        SELECT p.*, AVG(r.rating) AS avg_rating
        FROM products p
        LEFT JOIN reviews r ON p.id = r.product_id
        GROUP BY p.id
        HAVING avg_rating > 2  -- Only fetch products with average rating above 3
        ORDER BY avg_rating DESC";
        

    $result = $conn->query($query);
?>
