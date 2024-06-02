<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css"/>
    
    <title>LOGIN</title>
</head>
<body>
    <header>
        <img src="images/logo.png">
        <h1>AquaFlask</h1>
    </header>   
    <main>
        <div class="left">
            <h1>RESELLER MANAGEMENT SYSTEM</h1>
            <form action="index.php" method="post" >
                <input type="text" name="username" placeholder="  Username">
                <input type="password" name="password" placeholder="  Password" class="password">
                <input type="submit" name="login" value="LOGIN" class="login">
            </form>
          
        </div>
        <div class="right"></div>
    </main>
    
    <footer>
        <div class="companyWrapper">
            <img src="images/logo.png" />
            <div class="company">
            <p>AQUAFALSK WEBSITE</p>
            <a href="www.aquaflask.com" class="website">www.aquaflask.com </a>
            </div>
        </div>

        <div class="supportWrapper">
            <img src="images/support.png" />
            <div class="support">
            <p>Developer Support</p>
            <p>rmssupport@gmail.com</p>
            </div>
        </div>

        <div class="creditsWrapper">
            <img src="images/copyright.png" />
            <div class="credits">
            <p>2024 Reseller Management Sytem</p>
            <p>All rights reserved.</p>
            </div>
        </div>
    </footer>
    <img src="images/heroImg.png" class="hero">
</body>
</html>

<?php
    include 'connector.php';
    session_start();

    if(isset($_POST["login"])){
        $username = htmlspecialchars($_POST["username"]); 
        $password = htmlspecialchars($_POST["password"]);
        
        $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        
        if($result -> num_rows == 1){
            $row = $result->fetch_assoc();
            $_SESSION['admin_id'] = $row['admin_id'];
            header("Location: pages/resellerList.php");
        } else {
            echo '<script>alert("Invalid username or password.");</script>';
        }
    }
    $conn->close();
?>

