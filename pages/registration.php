<?php
include '../connector.php';
session_start();

if(isset($_POST["register"])){
    $name = htmlspecialchars($_POST["name"]); 
    $dob = htmlspecialchars($_POST["dob"]);
    $phoneNumber = htmlspecialchars($_POST["phoneNum"]);
    $email = htmlspecialchars($_POST["email"]);
    $postalCode = htmlspecialchars($_POST["postal"]);
    $region = ($_POST["region"]);
    $province = htmlspecialchars($_POST["province"]);
    
    $insert_address_sql = "INSERT INTO address (region_id, postal_code, province)
                          VALUES ('$region', '$postalCode', '$province')";

    if ($conn->query($insert_address_sql) === TRUE) {
        $address_id = $conn->insert_id;

        $insert_reseller_sql = "INSERT INTO reseller (name, email, phone_number, active_status, join_date, address)
                                VALUES ('$name', '$email', '$phoneNumber', 1, NOW(), $address_id)";
        if ($conn->query($insert_reseller_sql) === TRUE) {
            $reseller_id = $conn->insert_id;

            $insert_manages_sql = "INSERT INTO manages (date, admin_id, reseller_id, transaction_type)
                                   VALUES (NOW(), '{$_SESSION['admin_id']}', '$reseller_id', 1)";

            if ($conn->query($insert_manages_sql) === TRUE) {
                header("Location: resellerList.php");
                exit();
            } else {
                echo "Error recording relationship: " . $conn->error; 
            }
        } else {
            echo "Error inserting reseller: " . $conn->error;
        }
    } else {
        echo "Error inserting address: " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/registration.css"/>
    
   
    <title>RESELLER REGISTRATION</title>
  </head>
  <body>
    <header>
      <img src="../images/addReseller.png">
      <h1>RESELLER REGISTRATION</h1>
    </header>
    <main>
      <div class="backBtnWrapper">
        <a href="resellerList.php"><img src="../images/back.png" class="backBtn" /></a>
      </div>
      <form class="infoWrapper" action="registration.php" method="post" >
        <div class="left">
          <div class="personalInfo">
            <h3>Personal Information</h3>
            <input name="name" type="text" placeholder="  Name" />
            <input name="dob" type="date" placeholder="  Date of Birth" />
          </div>
          <div class="contactInfo">
            <h3>Contact Information</h3>
            <input name="email" type="email" placeholder="  Email" />
            <input name="phoneNum" type="tel" placeholder="  Phone Number" />
          </div>
        </div>
        <div class="right">
          <div class="address">
            <h3>Address</h3>
            <select  name="region">
              <option value="1">Ilocos Region</option>
              <option value="2">Cagayan Valley</option>
              <option value="3">Central Luzon</option>
              <option value="4">CALABARZON</option>
              <option value="5">Bicol Region</option>
              <option value="6">Western Visayas</option>
              <option value="7">Central Visayas</option>
              <option value="8">Eastern Visayas</option>
              <option value="9">Zamboanga Peninsula</option>
              <option value="10">Northern Mindanao</option>
              <option value="11">Davao Region</option>
              <option value="12">SOCCSKSARGEN</option>
              <option value="13">Caraga</option>
              <option value="14">Autonomous Region in Muslim Mindanao (ARMM)</option>
              <option value="15">Cordillera Administrative Region (CAR)</option>
              <option value="16">National Capital Region (NCR)</option>
              <option value="17">MIMAROPA</option>
              <option value="18">Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)</option>
            </select>

            <input name="province" type="text" placeholder="  Province" />
            <input name="postal" type="tel" placeholder="  Postal Code" />
            
            <div class="submitWrapper">
              <input
                name="register"
                type="submit"
                value="SUBMIT"
                class="submit"
              />
            </div>
          </div>
        </div>
      </form>
    </main>
    </body>
    <?php include 'footer.php'; ?>
</html> 
