<?php
require '../Include/connect.php';

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$query = "SELECT * FROM profileseller WHERE id = $id";
$result = mysqli_query($conn, $query);
$profile = mysqli_fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and get form values
    $name = isset($_POST["name"]) ? mysqli_real_escape_string($conn, $_POST["name"]) : 'N/A';
    $contact = isset($_POST["contact"]) ? mysqli_real_escape_string($conn, $_POST["contact"]) : 'N/A';
    $email = isset($_POST["email"]) ? mysqli_real_escape_string($conn, $_POST["email"]) : 'N/A';
    $address = isset($_POST["address"]) ? mysqli_real_escape_string($conn, $_POST["address"]) : 'N/A';

    // Prepare the update query to handle all fields
    $sql = "UPDATE profileseller 
            SET name = '$name', contact = '$contact', email = '$email', address = '$address' 
            WHERE id = $id";

    // Execute the update query
    if (mysqli_query($conn, $sql)) {
        $message = "Update successful";
    } else {
        $message = "Update Failed: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
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
                        <img src="../Image/<?php echo htmlspecialchars($profile['image']); ?>" alt="Current Image" width="100">
                        <label for="image">Insert Image</label>
                        <input type="file" name="image" id="image">
                    </div>
                </div>
                <div class="right-input">
                    <div class="form-group">
                        <input class="custom-input" placeholder=" " type="text" name="name" id="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>
                        <label for="name">Enter Name</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($profile['contact'] ?? ''); ?>">
                        <label for="contact">Enter Contact</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="email" name="email" id="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
                        <label for="email">Enter Email</label>
                    </div>
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="address" id="address" value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>">
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
