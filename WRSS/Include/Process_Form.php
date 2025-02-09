<?php
    include("connect.php"); 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $shopName = mysqli_real_escape_string($conn, $_POST['shopName']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $discount = mysqli_real_escape_string($conn, $_POST['discount']);
        $deliveryFee = mysqli_real_escape_string($conn, $_POST['deliveryFee']);
        $info = mysqli_real_escape_string($conn, $_POST['info']);
         

        if (empty($shopName) || empty($address) || empty($email) || empty($contact) || empty($price) || empty($discount) || empty($deliveryFee) || empty($info)){
            header("Location: ../Seller/ShopInfo.php?signup=empty");
            exit();
        } else if(!preg_match("/^[a-zA-Z0-9\s]*$/", $shopName) || !preg_match("/^[a-zA-Z0-9\s]*$/", $address) || !preg_match("/^[a-zA-Z0-9\s]*$/", $info)){
            header("Location: ../Seller/ShopInfo.php?signup=char");
            exit();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header("Location: ../Seller/ShopInfo.php?signup=email");
            exit();
        } 
        
        $stmt = $conn->prepare("INSERT INTO users (shopName, email, info) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $shopName, $email, $info);

        if ($stmt->execute()) {
            header("Location: ../Php/AfterSearch.php?signup=success");
        } else {
            echo "Query Failed: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid Request Method";
    }
?>
