<?php
include 'connector.php';

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Set the deleted status to false to unhide the product
    $new_deleted_status = false;

    // Update the product's deleted status in the database
    $update_sql = "UPDATE product SET deleted = ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $new_deleted_status, $product_id);

    if ($stmt->execute()) {
        // Redirect back to productList.php
        header("Location: productList.php");
        exit();
    } else {
        echo "Failed to unhide product.";
    }
} else {
    echo "Product ID not provided.";
}
?>
