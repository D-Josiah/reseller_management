<?php
include '../connector.php';

// Assuming you're receiving the form data via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $reseller_name = $_POST['reseller_name'];
    $province = $_POST['province']; // Assuming you're getting the province name from the form
    $postal_code = $_POST['postal_code'];
    $phone_number = $_POST['phone_number'];
    $age = $_POST['age'];
    $reseller_id = $_POST['reseller_id'];
    $active_status = $_POST['active_status'];
    $total_amount_spent = $_POST['total_amount_spent'];

    // Get the region ID from the address table based on the province name
    $region_query = "SELECT region_id FROM address WHERE province = '$province'";
    $region_result = $conn->query($region_query);

    if ($region_result->num_rows > 0) {
        $region_row = $region_result->fetch_assoc();
        $region_id = $region_row['region_id'];

        // Update query
        $sql = "UPDATE reseller
                SET name = '$reseller_name',
                    region_id = '$region_id',  
                    postal_code = '$postal_code',
                    phone_number = '$phone_number',
                    age = '$age',
                    province = '$province',
                    active_status = '$active_status',
                    total_amount_spent = '$total_amount_spent'
                WHERE reseller_id = $reseller_id";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Region not found";
    }

    $conn->close();
}
?>
