<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Design/manageProduct-add.css">
    <title>Add Product</title>
</head>
<body>
<div>
    <h1>Admin Portal</h1>
    <!-- Logout Button -->
    <a href="./action/logout.php" class="logout-link">Logout</a>
</div>
<div class="manage-prod-forms">
    
    <h2>Add New Product</h2>
    <form action="./action/addProduct_Admin.php" method="POST" enctype="multipart/form-data">
        
        <!-- Product Name -->
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" placeholder="Enter product name" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Enter product description" required></textarea>
        </div>

        <!-- Price -->
        <div class="form-group">
            <label for="price">Price (₱)</label>
            <input type="number" id="price" name="price" step="0.01" min="0" placeholder="Enter product price" required>
        </div>

        <!-- Image Upload -->
        <div class="form-group">
            <label for="image">Upload Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select a Category</option>
                <option value="choker">Choker</option>
                <option value="necklace">Necklace</option>
                <option value="bracelet">Bracelet</option>
                <option value="anklet">Anklet</option>
                <option value="earring">Earrings</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit">Add Product</button>
    </form>

    

    <a href="manageProduct_Page.php">View Products</a>
</div>

</body>
</html>
