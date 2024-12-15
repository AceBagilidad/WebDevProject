<?php
// Include the database connection
session_start();

include './config/dbconnection.php';


if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] != 1) {
    header("Location: ./index.php");
    exit;
}

// Handle product update
if (isset($_GET['edit']) && $_GET['edit'] == 1) {
    $edit_id = $_GET['edit_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $fetch_sql = "SELECT image_url FROM products WHERE id = ?";
    $stmt = $conn->prepare($fetch_sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $image_url = $row['image_url']; // Default to old image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $target_base_dir = "./Image/";
        $target_dir = "./uploads/";

        $file_name = basename($_FILES['image']['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_type, $valid_extensions) && $_FILES['image']['size'] <= 5000000) {
            $image_url = "./Image/" . ucfirst($category) . "/" . $file_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
        }
    }

    $update_sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, category = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssdssi", $name, $description, $price, $image_url, $category, $edit_id);

    if ($stmt->execute()) {
        header('Location: manageProduct_Page.php');
        exit();
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header('Location: manageProduct_Page.php?status=deleted');
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - Admin</title>
    <link rel="stylesheet" href="./Design/manageProduct-view.css">
</head>
<body>

    <div class="container">
        <h2>Manage Products</h2>

        <!-- Link to Add Product -->
        <a href="./addProduct_Page.php" class="btn">Add New Product</a>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price (₱)</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo "₱" . number_format($row['price'], 2); ?></td>
                            <td><?php echo ucfirst($row['category']); ?></td>
                            <td><img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" width="100"></td>
                            <td>
                                <a href="manageProduct_Page.php?edit_id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                <a href="manageProduct_Page.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>

                        <!-- Editable Form Row -->
                        <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] == $row['id'])    : ?>
                            <tr>
                                <td colspan="7">
                                    <h3>Edit Product</h3>
                                    <form action="manageProduct_Page.php?edit_id=<?php echo $row['id']; ?>&edit=1" method="POST" enctype="multipart/form-data">
                                        <table>
                                            <tr>
                                                <td>Product Name</td>
                                                <td><input type="text" name="name" value="<?php echo $row['name']; ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td>Description</td>
                                                <td><textarea name="description" required><?php echo $row['description']; ?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td>Price (₱)</td>
                                                <td><input type="number" name="price" value="<?php echo $row['price']; ?>" step="0.01" min="0" required></td>
                                            </tr>
                                            <tr>
                                                <td>Category</td>
                                                <td>
                                                    <select name="category" required>
                                                        <option value="choker" <?php echo ($row['category'] == 'choker' ? 'selected' : ''); ?>>Choker</option>
                                                        <option value="necklace" <?php echo ($row['category'] == 'necklace' ? 'selected' : ''); ?>>Necklace</option>
                                                        <option value="bracelet" <?php echo ($row['category'] == 'bracelet' ? 'selected' : ''); ?>>Bracelet</option>
                                                        <option value="anklet" <?php echo ($row['category'] == 'anklet' ? 'selected' : ''); ?>>Anklet</option>
                                                        <option value="earring" <?php echo ($row['category'] == 'earring' ? 'selected' : ''); ?>>Earring</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Product Image</td>
                                                <td><input type="file" name="image"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button type="submit" class="btn-submit">Update Product</button></td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
