<?php
include 'Include/connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Password validation
    if (strlen($password) < 8) {
        $message = "Password is too short";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        header("Location: Buyer-Registration.php");
    } elseif (!preg_match('/[0-9]/', $password)) {
        header("Location: Buyer-Registration.php");
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        header("Location: Buyer-Registration.php");
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email is already registered
        $check_email = "SELECT email FROM buyer WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email is already registered!";
        } else {
            // Insert new user
            $sql = "INSERT INTO buyer (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sss", $username, $email, $hashedPassword);

                if ($stmt->execute()) {
                    header("Location: Login.php");
                    exit();
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error preparing statement: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/registration-login.css">
    <link rel="stylesheet" href="Css/style.css">
    <script src="Script/Registration.js" async></script>
    <title>Registration</title>
</head>
<body>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#0099ff" fill-opacity="1" d="M0,96L13.3,112C26.7,128,53,160,80,181.3C106.7,203,133,213,160,218.7C186.7,224,213,224,240,186.7C266.7,149,293,75,320,69.3C346.7,64,373,128,400,176C426.7,224,453,256,480,272C506.7,288,533,288,560,250.7C586.7,213,613,139,640,122.7C666.7,107,693,149,720,186.7C746.7,224,773,256,800,250.7C826.7,245,853,203,880,197.3C906.7,192,933,224,960,202.7C986.7,181,1013,107,1040,85.3C1066.7,64,1093,96,1120,90.7C1146.7,85,1173,43,1200,48C1226.7,53,1253,107,1280,144C1306.7,181,1333,203,1360,224C1386.7,245,1413,267,1427,277.3L1440,288L1440,0L1426.7,0C1413.3,0,1387,0,1360,0C1333.3,0,1307,0,1280,0C1253.3,0,1227,0,1200,0C1173.3,0,1147,0,1120,0C1093.3,0,1067,0,1040,0C1013.3,0,987,0,960,0C933.3,0,907,0,880,0C853.3,0,827,0,800,0C773.3,0,747,0,720,0C693.3,0,667,0,640,0C613.3,0,587,0,560,0C533.3,0,507,0,480,0C453.3,0,427,0,400,0C373.3,0,347,0,320,0C293.3,0,267,0,240,0C213.3,0,187,0,160,0C133.3,0,107,0,80,0C53.3,0,27,0,13,0L0,0Z"></path>
      </svg>
    <div class="form-container">
        <a href="User-Buyer.php"><span class="close-button">&times;</span></a>
        <form action="Buyer-Registration.php" method="post">
            <h1>Create Account</h1>
            <h3>Buyer</h3>
            <?php if ($message): ?>
                   <p class='errors'><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
             <?php endif; ?> 
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Your username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Your Email Address" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Create a password" required>
            
            <div class="authentication">
                <ol>
                    <p>The password must contain more than 8</p>
                    <p>The password must contain at least 1 uppercase letter</p>
                    <p>The password must contain at least 1 special character</p>
                    <p>The password must contain at least 1 number</p>
                </ol>
            </div>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword" placeholder="Confirm password" required>

            <button type="submit" id="registerButton">Register</button>
        </form>
    </div>
</body>

</html>