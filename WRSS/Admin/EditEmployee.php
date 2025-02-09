<?php
require '../Include/connect.php';

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if an ID is provided
if (!isset($_GET['id'])) {
    echo "No ID provided!";
    exit();
}

// Fetch the record from the database
$id = intval($_GET['id']);
$query = "SELECT * FROM tb_upload WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "No record found!";
    exit();
}

// Handle form submission for updating the record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $info = mysqli_real_escape_string($conn, $_POST['info']);
    
    // Check if a new image is uploaded
    $image = $_FILES['image']['name'];
    
    // If a new image is uploaded, process the file
    if ($image) {
        // Specify the upload directory and move the uploaded file
        $uploadDir = '../Image/';
        $uploadFile = $uploadDir . basename($image);
        
        // Validate the uploaded file type (optional)
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                // Update the record with the new image
                $updateQuery = "UPDATE tb_upload SET name='$name', info='$info', image='$image' WHERE id=$id";
                if (mysqli_query($conn, $updateQuery)) {
                    header("Location: ../Admin/EmployeeInfo.php");
                    exit();
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF.";
        }
    } else {
        // Update the record without changing the image
        $updateQuery = "UPDATE tb_upload SET name='$name', info='$info' WHERE id=$id";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ../Admin/EmployeeInfo.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
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
    <script src="../Script/businessOwnerInfo.js"></script>
    <script src="../Script/Collapsible.js" async></script>
    <title>Edit Record</title>
</head>
<body>
    <nav>
        <ul class="home-ul">
            <li class="logo"><a href="../FrontPage.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a></li>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <div class="nav-center">
                <li class="nav-links"><a href="#about">ADMIN</a></li>
            </div>
            <li class="nav-button"><a href="">Register</a></li>
            <li class="nav-button"><a href="">Login</a></li>
        </ul>
    </nav>

    <div class="content-container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="site-title">
                <h1>Edit Record</h1>
            </div>
                <div class="personal">
                    <div class="personal-inside">
                        <div class="left-input">
                                <img src="../Image/<?php echo htmlspecialchars($row['image']); ?>" alt="Current Image" width="100">
                                <label for="image">Insert Image</label>
                                <input type= "file" name="image" id="image">
                            </div>
                    </div>
                    <div class="right-input">
                        <div class="form-group">
                            <input class="custom-input" placeholder=" " type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                            <label for="name">Enter Name</label>
                        </div>
                        <div class="form-group">
                            <input class="custom-input text-area" placeholder=" " type="text" name="info" id="info" value="<?php echo htmlspecialchars($row['info']); ?>">
                            <label for="info">Enter Info</label>
                        </div>
                        <div class="submit">
                           <button  type="submit" name="submit" class="button">Submit</button>
                           <a href="../Admin/EmployeeInfo.php" class="cancel">Cancel</a>
                        </div>
                    </div>
                </div>


        </form>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" style="z-index: -1;">
        <path fill="#0099ff" fill-opacity="1" d="M0,32L30,69.3C60,107,120,181,180,192C240,203,300,149,360,117.3C420,85,480,75,540,64C600,53,660,43,720,58.7C780,75,840,117,900,117.3C960,117,1020,75,1080,85.3C1140,96,1200,160,1260,160C1320,160,1380,96,1410,64L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320
        ,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
    </svg>
</body>
</html>
