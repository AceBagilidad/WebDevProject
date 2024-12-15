<?php
// Include the database connection
include '../config/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        // Set file size limits (in bytes)
        $minFileSize = 10 * 1024; // 10 KB (minimum file size)
        $maxFileSize = 5 * 1024 * 1024; // 5 MB (maximum file size)

        // Check file size
        $fileSize = $_FILES['image']['size'];
        if ($fileSize < $minFileSize) {
            echo "<script>alert('Error: File is too small. Minimum size is 10KB.'); window.history.back();</script>";
            exit();
        }
        if ($fileSize > $maxFileSize) {
            echo "<script>alert('Error: File is too large. Maximum size is 5MB.'); window.history.back();</script>";
            exit();
        }

        $target_base_dir = "../Image/";  // Go up one level and point to Image folder
        $target_dir = $target_base_dir . ucfirst($category) . "/";

        // Check if the folder exists or is being created
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);  // Create the folder if it doesn't exist
        }

        // Get the file name and extension
        $file_name = basename($_FILES['image']['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (!in_array($file_type, $valid_extensions)) {
            echo "<script>alert('Error: Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.'); window.history.back();</script>";
            exit();
        }

        // Use the original file name without adding uniqid() prefix
        $target_file = $target_dir . $file_name;

        // Store the relative URL for image display (to be used in the database)
        $image_url = "./Image/" . ucfirst($category) . "/" . $file_name;

        // Store the absolute path for the server (to save to the actual file system)
        $image_path = $target_file;

        // Attempt to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {

            // Insert product into the database
            $sql = "INSERT INTO products (name, description, price, image_url, category)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdss", $name, $description, $price, $image_url, $category);

            if ($stmt->execute()) {
                // Redirect with success message
                echo "<script>
                    alert('Product added successfully!');
                    window.location.href = '../addProduct_Page.php';
                </script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "<script>alert('Error uploading the image.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error with file upload. Please try again.'); window.history.back();</script>";
    }

    // Close the statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}

?>
