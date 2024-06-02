<?php
include '../connector.php';
session_start();

if(isset($_POST["submit"])){
    $reseller_name = htmlspecialchars($_POST["reseller_name"]); 
    $receiver = htmlspecialchars($_POST["receiver"]);
    $phoneNumber = htmlspecialchars($_POST["phoneNumber"]);
    $payment_method = htmlspecialchars($_POST["payment_method"]);
    $delivery_info = htmlspecialchars($_POST["delivery_info"]);

    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];

    $reseller_id = null;
    $total_price = 0;

    // Get reseller ID
    $get_id_sql = "SELECT * FROM reseller WHERE name = '$reseller_name'";
    $result = $conn->query($get_id_sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $reseller_id = $row['reseller_id'];
     } else {
        echo 'Error finding the reseller';
        exit();
     }

    // Insert order
    $insert_order_sql = "INSERT INTO orders (reseller_id, payment_method, order_status, 
                                    receiver, phone_number, delivery_info, total_price, date)
                          VALUES ('$reseller_id', '$payment_method', 2, '$receiver', '$phoneNumber',
                          '$delivery_info', $total_price, NOW())";
    if ($conn->query($insert_order_sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert order details
        foreach ($product_ids as $index => $product_id) {
            $quantity = $quantities[$index];
            $insert_product_sql = "INSERT INTO order_details (order_id, quantity, product_id)
                            VALUES ('$order_id', '$quantity', '$product_id')";
            if (!$conn->query($insert_product_sql)) {
                echo "Error recording order details: " . $conn->error;
                exit();
            }
        }

        // Calculate and update total price
        $get_total_sql = "SELECT SUM(quantity * price) AS total_price
                          FROM order_details
                          JOIN product ON order_details.product_id = product.product_id
                          WHERE order_details.order_id = $order_id";
        $total_price_result = $conn->query($get_total_sql);
        if($total_price_result->num_rows == 1){
            $row_result = $total_price_result->fetch_assoc();
            $total_price = $row_result['total_price'];

            $update_total_sql = "UPDATE orders SET total_price = '$total_price' WHERE order_id = '$order_id'";
            if ($conn->query($update_total_sql) === TRUE) {
                header("Location:orderList.php");
                exit();
            } else {
                echo 'Error updating total price: ' . $conn->error;
            }
        } else {
            echo 'Error calculating total price';
        }
    } else {
        echo "Error adding the order: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/addOrder.css"/>
    <link rel="stylesheet" href="css/footer.css"/>
    <title>ADD ORDER</title>
</head>
<body>
    <header>
    <a href="registration.php"><img src="../images/order.png"><a>
        <h1>ADD ORDER</h1>
    </header> 
    <main>
        <div class="backBtnWrapper">
            <a href="orderList.php"><img src="../images/back.png" class="backBtn" /></a>
        </div>
        <form action="addOrder.php" method="post" >
            <div class="left">
                <h3>Delivery Information</h3>
                <input name="reseller_name" type="text" placeholder="  Reseller Name" value=""/>
      
                <input name="receiver" type="text" placeholder="  Receiver's Name" value=""/>
                <input name="phoneNumber" type="tel" placeholder="  Receiver's Phone Number" value=""/>
                <select id="payment-method" name="payment_method" required>
                    <option value="">MODE OF PAYMENT</option>
                    <option value=1>Cash</option>
                    <option value=2>Gcash</option>
                    <option value=3>Bank</option>
                    <option value=4>Pending</option>
                </select>  
                <h3>Delivery Information</h3>
                <textarea name="delivery_info">Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae debitis possimus nostrum voluptate voluptas labore totam qui dolorem nemo. Perferendis recusandae facilis, iste deleniti deserunt excepturi doloribus ea ipsa illum!</textarea>
                 
   
            </div>
            <div class="right">
                <h3>Order Information</h3>
                
                <table class="orderList" >
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Quantity</th>
                        
                        </tr>
                    </thead>
                    <tbody>
                    <tr  class ="product_details" >
                        <td><input class ="product_details" type="text" name="id[]" value=""></td>
                        <td><input  class ="product_details" type="text" name="quantity[]" value=""></td>
                       
                    </tr>
                    <tr>
                        <td><input  class ="product_details" type="text" name="product_id[]" value=""></td>
                        <td><input class ="product_details"  type="text" name="quantity[]" value=""></td>
                     
                    </tr>
                    </tbody>
                </table> 
                
                <input name="submit" type="submit" class="submit" value="SUBMIT">
            </div>
        </form>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
