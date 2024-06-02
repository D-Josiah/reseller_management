<?php
include '../connector.php';

$sql = "SELECT o.order_id, o.date, r.name AS reseller, os.type AS status, o.delivery_info, o.total_price, pm.type AS payment_method
        FROM orders o
        JOIN reseller r ON o.reseller_id = r.reseller_id
        JOIN order_status os ON o.order_status = os.orderStatus_id
        JOIN payment_method pm ON o.payment_method = pm.paymentMethod_id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Order List</title>
    <script>
        // Function to apply filters based on status filter
        function applyFilters() {
            var statusFilter, table, tr, td, i;

            // Get the status filter value, converted to uppercase
            statusFilter = document.getElementById("statusFilter").value.toUpperCase();

            // Get the order list table and its rows
            table = document.getElementsByClassName("order-list")[0];
            tr = table.getElementsByClassName("order-item");

            // Loop through all table rows (orders)
            for (i = 0; i < tr.length; i++) {
                // Get the order info cell in the current row
                td = tr[i].getElementsByClassName("order-info-left")[0];
                if (td) {
                    // Get the status element within the order info cell
                    var status = td.getElementsByClassName("status")[0];

                    // Check if the order matches the status filter
                    if (status.textContent.toUpperCase().indexOf(statusFilter) > -1 || statusFilter === "") {
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
</head>
<body>
    <header>
        <div class="header-content">
            <img src="../images/admin.png" alt="persons" class="persons">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="resellerList.php">Reseller List</a></li>
            <li><a href="productList.php">Product List</a></li>
            <li><a href="orderList.php">Order List</a></li>
        </ul>
    </nav>

    <div class="search-filter-container">
        <select id="statusFilter" onchange="applyFilters()">
            <option value="">All</option>
            <option value="complete">Complete</option>
            <option value="pending">Pending</option>
            <option value="to_be_shipped">To be shipped</option>
        </select>
        <a href="addOrder.php"><button class="add-button">Add</button></a>
    </div>

    <main>
        <h2>Order List</h2>
        <div class="order-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<form action='orderInfo.php' method='post' class='order-item'>
                            <div class='order-icon'>
                                <img src='../images/product.png' alt='Cart' class='cart-icon'>
                            </div>
                            <div class='order-info-left'>
                                <p class='order-id'><strong>Order ID:</strong> {$row['order_id']}</p>
                                <p class='reseller'><strong>Reseller:</strong> {$row['reseller']}</p>
                                <p class='status'><strong>Status:</strong> {$row['status']}</p>
                            </div>
                            <div class='order-info-right'>
                                <p>----<strong>Order Details</strong>----</p>
                                <p><strong>Date:</strong> {$row['date']}</p>
                                <p><strong>Delivery Location:</strong> {$row['delivery_info']}</p>

                            </div>
                   
                            <input type='hidden' name='order_id' value='{$row['order_id']}'>
                            <a  href='orderInfo.php'><button   class='view-details-link' type ='submit'  name='view' > VIEW DETAILS </button><a/>
                          
                        </form>";
                }
            } else {
                echo "<p>No orders found</p>";
            }
            ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
