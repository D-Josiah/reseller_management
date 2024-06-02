<?php
include '../connector.php';

// Capture the filter value if set
$filter = isset($_GET['status']) ? $_GET['status'] : '';

// Modify the SQL query based on the filter
$sql = "SELECT p.product_id, p.stock, p.price, p.name, p.image, a.type AS availability
        FROM product p
        JOIN availability a ON p.availability = a.availability_id";

if ($filter) {
    $sql .= " WHERE a.type = '$filter'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Product List</title>
</head>
<script>
function applyFilters() {
    var statusFilter, table, tr, td, i;

    // Get the status filter value, converted to uppercase
    statusFilter = document.getElementById("statusFilter").value.toUpperCase();

    // Get the product list table and its rows
    table = document.getElementsByClassName("product-list")[0];
    tr = table.getElementsByClassName("product-card");

    // Loop through all table rows (products)
    for (i = 0; i < tr.length; i++) {
        // Get the product info cell in the current row
        td = tr[i].getElementsByClassName("product-info")[0];
        if (td) {
            // Get the availability element within the product info cell
            var availability = td.getElementsByClassName("details availability")[0];

            // Check if the product matches the status filter
            if (availability.textContent.toUpperCase().indexOf(statusFilter) > -1 || statusFilter === "") {
                // If matches, display the row
                tr[i].style.display = "";
            } else {
                // If not matches, hide the row
                tr[i].style.display = "none";
            }
        }
    }
}

</script>
<header>
    <div class="header-content">
        <img src="../images/admin.png" alt="persons" class="persons">
        <h1>Admin Dashboard</h1>
    </div>
</header>
<body>
    <nav>
        <ul>
            <li><a href="resellerList.php">Reseller List</a></li>
            <li><a href="productList.php">Product List</a></li>
            <li><a href="orderList.php">Order List</a></li>
        </ul>
    </nav>

    <div class="search-filter-container">
        <select id="statusFilter">
            <option value="">All</option>
            <option value="available">Available</option>
            <option value="limited stock">Limited Stock</option>
            <option value="out of stock">Out of Stock</option>
        </select>
        <button onclick="applyFilters()" class="apply-button">Apply</button>
        <a href="addProduct.php"><button class="add-button">Add</button></a>
    </div>

    <main>
        <h2>Product List</h2>
        <div class="product-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $productId = $row['product_id'];
                    $productName = $row['name'];
                    $productStatus = $row['availability'];
                    echo "<div class='product-card'>
                            <img src='{$row['image']}' alt='Product Image' class='product-image'>
                            <div class='product-info'>
                                <h3>Product ID: {$productId}</h3>
                                <p>Name: {$productName}</p>
                                <p class='details price'>Price: $ {$row['price']}</p>
                                <p class='details quantity'>Quantity: {$row['stock']}</p>
                                <p class='details availability'>Availability: {$productStatus}</p>
                                <a href='productInfo.php?product_id={$productId}' class='view-details-link'>View Details</a>
                                </div>
                            </div>";
                    }
            } else {
                echo "<p>No products found</p>";
            }
            $conn->close();
            ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
