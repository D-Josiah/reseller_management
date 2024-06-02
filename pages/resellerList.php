<?php
include '../connector.php';

// SQL query to select reseller data
$sql = "SELECT r.reseller_id, r.name, r.reseller_photo, a.type AS status
        FROM reseller r
        JOIN active_status a ON r.active_status = a.active_status_id";

// Execute the query
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Reseller List</title>
    <script>
        // Function to apply filters based on status filter
        function applyFilters() {
            var statusFilter, table, tr, td, i;

            // Get the status filter value, converted to uppercase
            statusFilter = document.getElementById("statusFilter").value.toUpperCase();

            // Get the reseller list table and its rows
            table = document.getElementsByClassName("reseller-list")[0];
            tr = table.getElementsByClassName("reseller-item");

            // Loop through all table rows (resellers)
            for (i = 0; i < tr.length; i++) {
                // Get the reseller info cell in the current row
                td = tr[i].getElementsByClassName("reseller-info")[0];
                if (td) {
                    // Get the status element within the reseller info cell
                    var status = td.getElementsByTagName("p")[2];

                    // Check if the reseller matches the status filter
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
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
        </select>
        <a href="registration.php"><button class="add-button">Add</button></a>
    </div>

    <main>
        <h2>Reseller List</h2>
        <div class="reseller-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='reseller-item'>
                        <div class='reseller-photo'>
                        <img src='reseller_icon.png' alt='Reseller Photo'>
                        </div>
                            <div class='reseller-info'>
                                <p><strong>ID:</strong> {$row['reseller_id']}</p>
                                <p><strong>Name:</strong> {$row['name']}</p>
                                <p><strong>Status:</strong> {$row['status']}</p>
                                <a href='resellerInfo.php?reseller_id={$row['reseller_id']}&name={$row['name']}&status={$row['status']}' class='view-details-link'>View Details</a>
                                </div>
                        </div>";
                }
            } else {
                echo "<p>No resellers found</p>";
            }
            $conn->close();
            ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>