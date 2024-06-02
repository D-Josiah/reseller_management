<?php
include '../connector.php';

if (isset($_POST['view'])) {
    $order_id = $_POST['order_id'];

    if (isset($order_id)) {
        $get_order_sql = "SELECT * FROM orders o 
        JOIN reseller r ON o.reseller_id = r.reseller_id 
        JOIN order_status os ON o.order_status = os.orderStatus_id 
        WHERE o.order_id = $order_id";

        $result = $conn->query($get_order_sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $reseller_name = htmlspecialchars($row['name']); 
            $receiver_name = htmlspecialchars($row['receiver']); 
            $receiver_number = htmlspecialchars($row['phone_number']); 
            $delivery_info = htmlspecialchars($row['delivery_info']); 
            $total = htmlspecialchars($row['total_price']); 
            $order_status = htmlspecialchars($row['type']);
            $order_date = htmlspecialchars($row['date']);  
            $order_id = $row['order_id']; 
            $payment_method = htmlspecialchars($row['payment_method']);  
        } else {
            echo "Cannot find order details";
        }
        
        $get_products_sql = "SELECT *
                             FROM order_details od
                             JOIN product p ON od.product_id = p.product_id
                             WHERE od.order_id = $order_id";
                             
        $products_result = $conn->query($get_products_sql);
    } else {
        echo "Order ID is not set";
    }
}

if (isset($_POST['save'])) {
    $order_id = $_POST['order_id'];
    

}    
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='css/orderInfo.css'/>
    <title>ORDER INFO</title>
</head>
<body>
    <header>
        <img src='../images/order.png' alt='Order'>
        <h1>ORDER INFO</h1>
    </header> 
    <nav>
        <ul>
            <li><a href='resellerList.php'>Reseller List</a></li>
            <li><a href='productList.php'>Product List</a></li>
            <li><a href='orderList.php'>Order List</a></li>
        </ul>
    </nav>
    <main>
        <div class='backBtnWrapper'>
            <a href='orderList.php'><img src='../images/back.png' class='backBtn' alt='Back' /></a>
        </div>
        <form method='POST' action='orderInfo.php'>
            <input type='hidden' name='order_id' value='     <?php echo $order_id; ?>'/>
            <div class='left'>
                <h3>Delivery Information</h3>
                <label for='name'>Reseller Name</label>
                <input name='name' type='text' value='     <?php echo $reseller_name; ?>'/>
                <label for='receiver'>Receiver's Name</label>
                <input name='receiver' type='text' value='     <?php echo $receiver_name; ?>'/>
                <label for='phoneNumber'>Receiver's Phone Number</label>
                <input name='phoneNumber' type='tel' value='     <?php echo $receiver_number; ?>'/>
                <select id='payment_method' name='payment_method' required>
                    <option value='' disabled <?php if (empty($payment_method)) echo 'selected'; ?>>MODE OF PAYMENT</option>
                    <option value='1' <?php if ($payment_method == '1') echo 'selected'; ?>>Cash</option>
                    <option value='2' <?php if ($payment_method == '2') echo 'selected'; ?>>Gcash</option>
                    <option value='3' <?php if ($payment_method == '3') echo 'selected'; ?>>Bank</option>
                    <option value='4' <?php if ($payment_method == '4') echo 'selected'; ?>>Pending</option>
                </select>
                <h3>Delivery Information</h3>
                <textarea><?php echo $delivery_info; ?></textarea>
            </div>
            <div class='right'>
                <h3>Order Information</h3>
                <table class='orderList'>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($products_result) && $products_result->num_rows > 0) {
                            while ($product_row = $products_result->fetch_assoc()) {
                                $total_price = $product_row['quantity'] * $product_row['price'];
                                echo "<tr>
                                        <td><input type='text'  name='product_id[]' value='     " . htmlspecialchars($product_row['product_id']) . "' readonly></td>
                                        <td><input type='text' name='product_name[]' value='     " . htmlspecialchars($product_row['name']) . "' readonly></td>
                                        <td><input type='number' name='quantity[]' value='     " . htmlspecialchars($product_row['quantity']) . "'></td>
                                        <td><input type='text' name='price[]' value='     " . htmlspecialchars($product_row['price']) . "' readonly></td>
                                        <td><input type='text' name='total[]' value='     " . htmlspecialchars($total_price) . "' readonly></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No products found for this order.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table> 

                <h3>Order Total:      <?php echo $total; ?></h3>
                <h3>Date ordered:      <?php echo $order_date; ?></h3>

                <h3>Order Status</h3>
                <select id='order_status' name='order_status'>
                    <option value='COMPLETED' <?php if ($order_status == 'COMPLETED') echo 'selected'; ?>>COMPLETED</option>
                    <option value='PENDING APPROVAL' <?php if ($order_status == 'PENDING APPROVAL') echo 'selected'; ?>>PENDING APPROVAL</option>
                    <option value='TO BE SHIPPED' <?php if ($order_status == 'TO BE SHIPPED') echo 'selected'; ?>>TO BE SHIPPED</option>
                    <option value='CANCELED' <?php if ($order_status == 'CANCELED') echo 'selected'; ?>>CANCELED</option>
                </select>
                <input type='submit' class='submit' name='save' value='SAVE CHANGES'>
            </div>
        </form>
    </main>
<?php include 'footer.php'; ?>
</body>
</html>
