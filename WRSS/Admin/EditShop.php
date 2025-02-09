<?php
require '../Include/connect.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    echo "No ID provided!";
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM shopinfo WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "No record found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $info = mysqli_real_escape_string($conn, $_POST['info']);

   
    $profilePic = $_FILES['profilePic']['name'];
    $permit = $_FILES['permit']['name'];
    
    $uploadDir = '../Image/';
    
    if ($profilePic) {
        $profileUploadFile = $uploadDir . basename($profilePic);
        $fileType = strtolower(pathinfo($profileUploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $profileUploadFile)) {
                // Update the record with the new profile picture
                $updateQuery = "UPDATE shopinfo SET name='$name', info='$info', profilePic='$profilePic' WHERE id=$id";
                if (!mysqli_query($conn, $updateQuery)) {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "Error uploading the profile picture.";
            }
        } else {
            echo "Invalid profile picture type. Please upload a JPG, JPEG, PNG, or GIF.";
        }
    }
    
    if ($permit) {
        $permitUploadFile = $uploadDir . basename($permit);
        $fileType = strtolower(pathinfo($permitUploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['permit']['tmp_name'], $permitUploadFile)) {
                
                $updateQuery = "UPDATE shopinfo SET permit='$permit' WHERE id=$id";
                if (!mysqli_query($conn, $updateQuery)) {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "Error uploading the permit.";
            }
        } else {
            echo "Invalid permit type. Please upload a JPG, JPEG, PNG, or GIF.";
        }
    }
    
    if (!$profilePic && !$permit) {
        $updateQuery = "UPDATE shopinfo SET name='$name', address='$address', email='$email', contact='$contact', info='$info' WHERE id=$id";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ShopInfo.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        header("Location: ShopInfo.php");
        exit();
    }
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
    <script src="../Script/businessOwnerInfo.js" async></script>
    <script src="../Script/Collapsible.js" async></script>
    <title>Edit Shop</title>
</head>
<body>
    <nav>
        <ul class="home-ul">
            <li class="logo"><a href="../FrontPage.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a></li>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <div class="nav-center">
                <li class="nav-links"><a href="#about">ADMIN</a></li>
            </div>
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
            <li>Dashboard</li>
            <li>Orders</li>
            <li class="collapsible">Information</li>
            <div class="info">
                <i><a href="#">Buyer</a></i>
                <i><a href="#">Seller</a></i>
                <i><a href="EmployeeInfo.php">Employee</a></i>
            </div>
        </ul>
    </div>

    <!-- Shop Edit Table -->
    <div class="content-container">
    <div class="site-title">
            <h1>Edit Shop</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="personal">
                <div class="personal-inside">
                    <div class="left-input">
                        <img src="../Image/<?php echo htmlspecialchars($row['profilePic']); ?>" alt="Current Image" width="100">
                        <label for="input-profile">Update Profile</label>
                        <input type="file" name="profilePic" id="input-profile">
                    </div>
                    <div class="left-input">
                        <img src="../Image/<?php echo htmlspecialchars($row['permit']); ?>" alt="Current Image" width="100">
                        <label for="input-permit">Update Permit</label>
                        <input type="file" name="permit" id="input-permit">
                    </div>
                </div>
                <div class="right-input">
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                        <label for="name">Enter Name</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="address" id="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
                        <label for="address">Address</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="email" name="email" id="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="number" name="contact" id="contact" value="<?php echo htmlspecialchars($row['contact']); ?>" required>
                        <label for="contact">Contact no.</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="info" id="info" value="<?php echo htmlspecialchars($row['info']); ?>">
                        <label for="info">Enter Info</label>
                    </div>
                    <div class="submit">
                        <button type="submit" name="submit" class="button">Submit</button>
                        <a href="ShopInfo.php" class="cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
