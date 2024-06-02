<?php
include '../connector.php';

// Retrieve product details based on the product ID
if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Prepare and execute SQL query
    $sql = "SELECT * FROM product WHERE product_id = $product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Product Information</title>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="persons2.png" alt="persons" class="persons">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <main>
        <h2>Product Information</h2>
        <form action="update_product.php" method="post" enctype="multipart/form-data">
            <div class="form-columns">
                <div class="left-column">
                    <label for="product-id">Product ID</label>
                    <input type="number" id="product-id" name="product-id" value="<?php echo isset($_GET['product_id']) ? $_GET['product_id'] : ''; ?>" required>

                    <label for="product-price">Price</label>
                    <input type="number" id="product-price" name="product-price" value="<?php echo $row['price']; ?>" step="0.01" required>

                    <label for="stock">Quantity</label>
                    <input type="number" id="stock" name="stock" value="<?php echo $row['stock']; ?>" required>

                    <label for="product-status">Status</label>
                    <select id="product-status" name="product-status">
                        <option value="available" <?php if ($row['availability'] == 'available') echo 'selected'; ?>>Available</option>
                        <option value="limited stock" <?php if ($row['availability'] == 'limited stock') echo 'selected'; ?>>Limited Stock</option>
                        <option value="out of stock" <?php if ($row['availability'] == 'out of stock') echo 'selected'; ?>>Out of Stock</option>
                    </select>

                    <label for="product-photo">Change Photo</label>
                    <input type="file" id="product-photo" name="product-photo" accept="image/*">

                    <!-- Display image preview -->
                    <?php echo "<img src='".htmlspecialchars($row['image'])."' alt='Product Image' class='product-image'>"; ?>

                </div>
                <div class="right-column">
                    <label for="product-description">Product Name</label>
                    <textarea id="product-description" name="product-description" required style="resize: none; height: 150px; width: 300px;"><?php echo htmlspecialchars($row['description']); ?></textarea>

                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="submit" value="Save Changes" class="save-button">
                </div>
            </div>
        </form>
    </main>
    
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
    } else {
        echo "No product found with ID: " . htmlspecialchars($product_id);
    }
} else {
    echo "Product ID not provided";
}
$stmt->close();
$conn->close();
?>
