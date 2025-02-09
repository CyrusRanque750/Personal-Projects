<?php
require '../Include/connect.php';

$message = '';

if (isset($_POST["submit"])) {
    $name = trim($_POST["name"]);
    $info = trim($_POST["info"]);

    if (empty($name) || empty($info)) {
        $message = "Please fill out all fields.";
    } elseif ($_FILES["image"]["error"] === 4) {
        $message = "Image does not exist.";
    } else {
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'png', 'jpeg'];
        $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $imageExtension = strtolower($imageExtension);

        if (!in_array($imageExtension, $validImageExtension)) {
            $message = "Invalid image extension. Only jpg, png, jpeg are allowed.";
        } elseif ($fileSize > 1000000) {
            $message = "Image is too large. Max size is 1MB.";
        } else {
            $newImageName = uniqid() . '.' . $imageExtension;
            $uploadPath = '../Image/' . $newImageName;

            if (move_uploaded_file($tmpName, $uploadPath)) {
                $query = "INSERT INTO tb_upload (name, image, info) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'sss', $name, $newImageName, $info);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "You have successfully uploaded.";
                    header("Location: Employee.php");
                    exit();
                } else {
                    $message = "Failed to upload. Please try again.";
                }
            } else {
                $message = "Failed to move uploaded image.";
            }
        }
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
    <link rel="stylesheet" href="../Css/Employee.css">
    <script src="../Script/Employee.js" async></script>
    <script src="../Script/Collapsible.js" async></script>
    <title>After Search</title>
</head>
<body>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#0099ff" fill-opacity="1" d="M0,96L13.3,112C26.7,128,53,160,80,181.3C106.7,203,133,213,160,218.7C186.7,224,213,224,240,186.7C266.7,149,293,75,320,69.3C346.7,64,373,128,400,176C426.7,224,453,256,480,272C506.7,288,533,288,560,250.7C586.7,213,613,139,640,122.7C666.7,107,693,149,720,186.7C746.7,224,773,256,800,250.7C826.7,245,853,203,880,197.3C906.7,192,933,224,960,202.7C986.7,181,1013,107,1040,85.3C1066.7,64,1093,96,1120,90.7C1146.7,85,1173,43,1200,48C1226.7,53,1253,107,1280,144C1306.7,181,1333,203,1360,224C1386.7,245,1413,267,1427,277.3L1440,288L1440,0L1426.7,0C1413.3,0,1387,0,1360,0C1333.3,0,1307,0,1280,0C1253.3,0,1227,0,1200,0C1173.3,0,1147,0,1120,0C1093.3,0,1067,0,1040,0C1013.3,0,987,0,960,0C933.3,0,907,0,880,0C853.3,0,827,0,800,0C773.3,0,747,0,720,0C693.3,0,667,0,640,0C613.3,0,587,0,560,0C533.3,0,507,0,480,0C453.3,0,427,0,400,0C373.3,0,347,0,320,0C293.3,0,267,0,240,0C213.3,0,187,0,160,0C133.3,0,107,0,80,0C53.3,0,27,0,13,0L0,0Z"></path>
    </svg>
    <nav>
        <ul class="home-ul">
            <li class="logo"><a href="../FrontPage.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a></li>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <div class="nav-center">
                <li class="nav-links"><a href="#about">SELLER</a></li>
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

<!-- Images -->    
        <div class="content-container form-container">
            <form action="Employee.php" method="post" autocomplete="off" enctype="multipart/form-data">
                 <?php if ($message): ?>
                <p class='error'><?php echo $message; ?></p>
            <?php endif; ?>
                <div class="site-title">
                    <h1>Employee Information</h1>
                </div> 
                <div class="personal">
                   <div class="personal-inside">
                        <div class="left-input">
                                <img src="../Image/UserIcon.jpg" id="profile-pic">
                                <label for="image">Insert Image</label>
                                <input type= "file" accept=".jpg, .jpeg, .png" name="image" id="image" value="">
                            </div>
                   </div>
<!-- Input Box -->
                <div class="right-input">
                        <div class="form-group">
                            <input class="custom-input" placeholder=" " type="text" name="name" id="name" required>
                            <label for="name">Enter Name</label>
                        </div>
                        <div class="form-group">
                            <input class="custom-input text-area" placeholder=" " type="text" name="info" id="info" required>
                            <label for="info">Enter Info</label>
                        </div>
                        <div>
                           <button  type="submit" name="submit" class="button">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
<style>
    
</style>
</body>
</html>
