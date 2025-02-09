<?php
require '../Include/connect.php';

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST["submit"])) {
    // Prepare input data
    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);
    $contact = trim($_POST["contact"]);
    $price = trim($_POST["price"]);
    $discount = trim($_POST["discount"]);
    $deliveryFee = trim($_POST["deliveryFee"]);
    $info = trim($_POST["info"]);

    // Check both images: profile-pic and permit
    $images = ['profilePic' => null, 'permit' => null];
    foreach ($images as $key => &$newImageName) {
        if ($_FILES[$key]["error"] === 4) {
            echo "<script>alert('Image for $key does not exist');</script>";
            exit; // Stop if any required image is missing
        } else if ($_FILES[$key]["error"] !== 0) {
            echo "<script>alert('Error uploading image for $key: " . $_FILES[$key]["error"] . "');</script>";
            exit;
        } else {
            $fileName = $_FILES[$key]["name"];
            $fileSize = $_FILES[$key]["size"];
            $tmpName = $_FILES[$key]["tmp_name"];

            $validImageExtension = ['jpg', 'png', 'jpeg'];
            $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($imageExtension, $validImageExtension)) {
                echo "<script>alert('Invalid Image Extension for $key');</script>";
                exit;
            } else if ($fileSize > 1000000) {
                echo "<script>alert('Image for $key is too large');</script>";
                exit;
            } else {
                $newImageName = uniqid() . '.' . $imageExtension;
                if (!move_uploaded_file($tmpName, '../Image/' . $newImageName)) {
                    echo "<script>alert('Failed to move uploaded file for $key');</script>";
                    exit;
                }
            }
        }
    }

    // Prepare and execute the insert query with prepared statements
    $stmt = $conn->prepare("INSERT INTO shopinfo (name, profilePic, permit, address, email, contact, price, discount, deliveryFee, info) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssiddss', $name, $images['profilePic'], $images['permit'], $address, $email, $contact, $price, $discount, $deliveryFee, $info);

    // Debugging statement
    if ($stmt->execute()) {
        echo "Data inserted successfully."; // Debug statement
        header("Location: Shop.php");
        exit; // Ensure no further code is executed after the redirect
    } else {
        echo "Error: " . $stmt->error; // Debug statement for SQL error
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@533&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/ShopInfo.css">
    <script src="../Script/Collapsible.js" async></script>
    <script src="../Script/NavBar.js" async></script>
    <title>Shop Information</title>
</head>
<body>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#0099ff" fill-opacity="1" d="M0,96L13.3,112C26.7,128,53,160,80,181.3C106.7,203,133,213,160,218.7C186.7,224,213,224,240,186.7C266.7,149,293,75,320,69.3C346.7,64,373,128,400,176C426.7,224,453,256,480,272C506.7,288,533,288,560,250.7C586.7,213,613,139,640,122.7C666.7,107,693,149,720,186.7C746.7,224,773,256,800,250.7C826.7,245,853,203,880,197.3C906.7,192,933,224,960,202.7C986.7,181,1013,107,1040,85.3C1066.7,64,1093,96,1120,90.7C1146.7,85,1173,43,1200,48C1226.7,53,1253,107,1280,144C1306.7,181,1333,203,1360,224C1386.7,245,1413,267,1427,277.3L1440,288L1440,0L1426.7,0C1413.3,0,1387,0,1360,0C1333.3,0,1307,0,1280,0C1253.3,0,1227,0,1200,0C1173.3,0,1147,0,1120,0C1093.3,0,1067,0,1040,0C1013.3,0,987,0,960,0C933.3,0,907,0,880,0C853.3,0,827,0,800,0C773.3,0,747,0,720,0C693.3,0,667,0,640,0C613.3,0,587,0,560,0C533.3,0,507,0,480,0C453.3,0,427,0,400,0C373.3,0,347,0,320,0C293.3,0,267,0,240,0C213.3,0,187,0,160,0C133.3,0,107,0,80,0C53.3,0,27,0,13,0L0,0Z"></path>
    </svg>
    <nav>
        <ul class="home-ul">
            <a href="Shop.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <li class="nav-button"><a href="../FrontPage.php">Logout</a></li>
        </ul>
    </nav>

    
    <!-- Side Bar -->
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">
    <i class="fa fa-bars" aria-hidden="true"></i>
    </label>
    <div class="slideout-sidebar">
        <ul>
            <li><a href="ProfileSeller.php">Profile</a></li>
            <li><a href="SellerDashboard.php">Dashboard</a></li>
            <li class="collapsible">Information</li>
            <div class="info">
                <i><a href="Orders.php">Orders</a></i>
                <i><a href="Shop.php">Shop</a></i>
                <i><a href="Employee.php">Employee</a></i>
                <i><a href="Reviews.php">Reviews</a></i>
            </div>
        </ul>
    </div>

    <!-- Image -->
    <div class="content-container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="site-title">
                <h1>Shop Information</h1>
            </div>
            <div class="personal column">
                <div class="personal-inside">
                    <div class="left-input">
                        <img src="../Image/UserIcon.jpg" id="profilePic">
                        <label for="input-profile">Insert Profile</label>
                        <input type="file" accept="image/jpeg, image/png" name="profilePic" id="input-profile" required>
                    </div>
                    <div class="left-input">
                        <img src="../Image/UserIcon.jpg" id="permit">
                        <label for="input-permit">Insert Permit</label>
                        <input type="file" accept="image/jpeg, image/png" name="permit" id="input-permit" required>
                    </div>
                </div>

                <!-- Input Box -->
                <div class="middle-input column">
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="name" id="name" required>
                        <label for="name">Shop Name</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="address" id="address" required>
                        <label for="address">Address</label>
                    </div>
                    <div class="amount">
                        <div class="form-group column1">
                            <input class="custom-input-number" placeholder=" " type="number" name="price" id="price" required>
                            <label for="price">Price</label>
                        </div>
                        <div class="form-group column1">
                            <input class="custom-input-number" placeholder=" " type="number" name="discount" id="discount" required>
                            <label for="discount">Discount</label>
                        </div>
                        <div class="form-group column1">
                            <input class="custom-input-number" placeholder=" " type="number" name="deliveryFee" id="deliveryFee" required>
                            <label for="deliveryFee">Delivery Fee</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="info" id="info" required>
                        <label for="info">Information</label>
                    </div>
                    <div>
                        <button type="submit" name="submit" class="button">Submit</button>
                        <button class="return"> <a href="SellerDashboard.php">Go back</a></button>
                    </div>
                </div>
                <div class="right-input column">
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="email" name="email" id="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="contact" id="contact" required>
                        <label for="contact">Contact no.</label>
                    </div>
                </div>
            </div>
        </form>
    </div>

   
    <script src="../Script/ShopInfo.js" async></script>
</body>
</html>
