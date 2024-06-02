<!DOCTYPE html>
<head>

    <title>Reseller Info</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <div class="header-content">
            <img src="persons.png" alt="persons" class="persons">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <h1>RESELLER INFO</h1>
    <?php
    include '../connector.php';

    // Assuming reseller_id is passed via GET parameter
    $reseller_id = $_GET['reseller_id'];
    $sql = "SELECT * FROM reseller WHERE reseller_id = $reseller_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    ?>
    <form action="update_reseller.php" method="post">
        <div class="reseller-icon">
            <img src="reseller_icon.png" alt="reseller icon">
        </div>
        <br>

        <div class="form-columns">
            <div>
                <label for="reseller_name">Reseller Name:</label>
                <input type="text" id="reseller_name" name="reseller_name" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
            </div>
            <div>
                <label for="region">Region:</label>
                <select id="region" name="region">
                    <option value="">Select Region</option>
                    <?php
                    // Fetch regions from the address table
                    $region_sql = "SELECT * FROM address";
                    $region_result = $conn->query($region_sql);
                    if ($region_result->num_rows > 0) {
                        while ($region_row = $region_result->fetch_assoc()) {
                            echo "<option value='".$region_row['region_id']."'>".$region_row['province']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" value="<?php echo isset($row['postal_code']) ? $row['postal_code'] : ''; ?>">
            </div>
        </div>
        <div class="form-columns">
            <div>
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo isset($row['phone_number']) ? $row['phone_number'] : ''; ?>">
            </div>
            <div>
                <label for="age">Age:</label>
                <input type="text" id="age" name="age" value="<?php echo isset($row['age']) ? $row['age'] : ''; ?>">
            </div>

            <div>
                <label for="province">Province:</label>
                <input type="text" id="province" name="province" value="<?php echo isset($row['province']) ? $row['province'] : ''; ?>">
            </div>
        </div>
        <div class="form-columns">
            <div>
                <label for="reseller_id">Reseller ID:</label>
                <input type="text" id="reseller_id" name="reseller_id" value="<?php echo isset($row['reseller_id']) ? $row['reseller_id'] : ''; ?>" readonly>
            </div>
            <div>
                <label for="active_status">Active Status:</label>
                <select id="active_status" name="active_status">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <div>
                <label for="total_amount_spent">Total Amount Spent:</label>
                <input type="text" id="total_amount_spent" name="total_amount_spent" value="<?php echo isset($row['total_amount_spent']) ? $row['total_amount_spent'] : ''; ?>">
            </div>
        </div>
        <input type="submit" value="SAVE CHANGES" name="save_changes">
    </form>
    <?php
    } else {
        echo "No reseller found with ID: $reseller_id";
    }
    ?>
    <h2>ORDERS</h2>
    <!-- Display orders for the reseller here -->

    <?php include 'footer.php'; ?>

</body>
</html>
