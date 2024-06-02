<?php
include 'connector.php';

// Check if form data is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $product_id = $_POST['product-id'];
    $product_price = $_POST['product-price'];
    $stock = $_POST['stock'];
    $product_description = $_POST['product-description'];
    $product_status = $_POST['product-status'];
    
    // Check if a new image is uploaded
    if ($_FILES['product-photo']['error'] === 0) {
        // File upload successful, process the image
        $image = $_FILES['product-photo']['tmp_name'];
        $image_content = addslashes(file_get_contents($image));

        // Update product information with new image and status
        $sql = "UPDATE product SET price = ?, stock = ?, description = ?, image = ?, availability = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsssii", $product_price, $stock, $product_description, $image_content, $product_status, $product_id);
    } else {
        // No new image uploaded, update product information without changing the image but with the status
        $sql = "UPDATE product SET price = ?, stock = ?, description = ?, availability = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsssi", $product_price, $stock, $product_description, $product_status, $product_id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        $message = "Product information updated successfully";
    } else {
        $error = "Error updating product information: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If form data is not submitted via POST, redirect back to the product info page
    header("Location: productInfo.php?product_id=" . $_POST['product_id']);
    exit();
}
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Update Product</title>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="persons.png" alt="persons" class="persons">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <main>
    <?php 
    if (isset($message)) {
        echo "<div class='message-container' style='text-align: center;'><div class='success-message'><strong>$message</strong></div></div>";
        echo "<div class='button-container' style='text-align: center;'><a href='productList.php' class='view-details-link'>Return to Product List</a></div>";
    } elseif (isset($error)) {
        echo "<div class='message-container' style='text-align: center;'><div class='error-message'><strong>$error</strong></div></div>";
    }
    ?>
    </main>
    
    <?php include 'footer.html'; ?>
</body>
</html>
