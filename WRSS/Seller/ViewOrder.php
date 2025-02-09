<?php
require '../Include/connect.php'; // Include the database connection

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the order ID from the URL
if (isset($_GET['id'])) {
    $orderId = intval($_GET['id']); // Sanitize the input for security

    // Fetch order details from the database
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the order exists
    if ($result && mysqli_num_rows($result) > 0) {
        $order = mysqli_fetch_assoc($result);
    } else {
        echo "Order not found.";
        exit();
    }
} else {
    echo "No order ID specified.";
    exit();
}

// Handle the message sending action
if (isset($_POST['send_message'])) {
    $deliveryMessage = mysqli_real_escape_string($conn, $_POST['delivery_message']);
    $buyerEmail = $order['email']; // Get the buyer's email

    // You could also send an email or update the order table with the message.
    // For simplicity, we'll update the order with a 'delivery_message' column (you need to add this to the database table).

    $updateQuery = "UPDATE orders SET delivery_message = ? WHERE id = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, 'si', $deliveryMessage, $orderId);
    mysqli_stmt_execute($updateStmt);

    // Check if the message was successfully saved
    if (mysqli_stmt_affected_rows($updateStmt) > 0) {
        echo "<script>alert('Message sent to buyer!');</script>";
    } else {
        echo "<script>alert('Failed to send message.');</script>";
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
    <link rel="stylesheet" href="../Css/EmployeeInfo.css">
    <script src="../Script/NavBar.js" async></script>
    <script src="../Script/Collapsible.js" async></script>
    <title>View Order</title>
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

    <!-- Container -->
    <div class="content-container">
        <div class="site-title">
            <h1>Order Details</h1>
        </div>
        
        <div class="order-details">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
            <p><strong>Shop Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact']); ?></p>
            <p><strong>Info:</strong> <?php echo htmlspecialchars($order['info']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['method']); ?></p>
            <p><strong>Amount:</strong> ₱<?php echo htmlspecialchars($order['amount']); ?></p>
            <p><strong>Total:</strong> ₱<?php echo number_format($order['total'], 2); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
        </div>

        <form action="" method="post">
            <label for="delivery_message"><strong>Enter Delivery Message:</strong></label>
            <textarea name="delivery_message" id="delivery_message" rows="4" cols="50" required></textarea><br><br>
            <input type="submit" name="send_message" value="Send Message" class="send-message-button">
        </form>

        <br>
        <a href="Orders.php" class="back-button">Back to Orders</a>
    </div>
</body>
</html>
