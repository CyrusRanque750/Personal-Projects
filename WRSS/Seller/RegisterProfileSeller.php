<?php
include '../Include/connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $contact = mysqli_real_escape_string($conn, $_POST["contact"]);
    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);

    if ($_FILES["profile"]["error"] === 4){
        $message = "Image not found";
    }else {
        $fileName = $_FILES["profile"]["name"];
        $fileSize = $_FILES["profile"]["size"];
        $tmpName = $_FILES["profile"]["tmp_name"];

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
                $query = "INSERT INTO profileseller (name, profile, contact, email, address) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'sssss', $name, $newImageName, $contact, $email, $address);

                if (mysqli_stmt_execute($stmt)) {
                    $message = "You have successfully uploaded.";
                    header("Location: ProfileSeller.php");
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
                <h1>Register Info</h1>
            </div>
            <div class="personal">
                <div class="personal-inside">
                    <div class="left-input">
                        <img src="../Image/<?php echo htmlspecialchars($profile); ?>" alt="Current Image" width="100" >
                        <label for="profile">Insert Image</label>
                        <input type="file" name="profile" id="profile" required>
                    </div>
                </div>
                <div class="right-input">
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="name" id="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>
                        <label for="name">Enter Name</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($profile['contact'] ?? ''); ?>" required>
                        <label for="contact">Enter Contact</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="email" name="email" id="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" required>
                        <label for="email">Enter Email</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="address" id="address" value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>" required>
                        <label for="address">Enter Address</label>
                    </div>
                    <div class="submit">
                        <button type="submit" name="submit" class="button">Submit</button>
                        <a href="ProfileSeller.php" class="cancel">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
