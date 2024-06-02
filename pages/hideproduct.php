<?php
include 'connector.php';

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Update the product to mark it as deleted
    $sql = "UPDATE product SET deleted = TRUE WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Redirect back to productList.php
        header("Location: productList.php");
        exit();
    } else {
        echo "Failed to hide product.";
    }

    $stmt->close();
}

$conn->close();
?>
