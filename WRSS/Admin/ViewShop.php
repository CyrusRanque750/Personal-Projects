<?php
require '../Include/connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$sellerId = $_SESSION['id'] ?? null;

//Check if sellerId is valid
if (!$sellerId) {
    echo "Seller not logged in.";
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = "SELECT * FROM shopinfo WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); // Bind the ID parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the shop exists
    if ($result && $result->num_rows > 0) {
        $shop = $result->fetch_assoc();
    } else {
        echo "Shop not found.";
        exit();
    }
} else {
    echo "Invalid shop ID.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        // Update shop info with seller_id
        $updateQuery = "UPDATE shopinfo SET status = 'approved', seller_id = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $sellerId, $id); // Link seller ID
        
        if ($updateStmt->execute()) {
            header("Location: ShopInfo.php"); 
            exit();
        } else {
            echo "Error approving shop: " . $conn->error;
        }
    } elseif (isset($_POST['reject'])) {
        // Update shop info with seller_id even when rejecting
        $updateQuery = "UPDATE shopinfo SET status = 'rejected', seller_id = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $sellerId, $id); // Link seller ID

        if ($updateStmt->execute()) {
            echo "<script>alert('The shop has been rejected.'); window.location.href='ShopInfo.php';</script>"; // Notify and redirect
            exit();
        } else {
            echo "Error rejecting shop: " . $conn->error;
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@533&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/ViewShop(2).css">
    <title>View Shop</title>
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

    <!-- Shop Info Section -->
    <div class="content-container">
            <div class="site-title">
                <h1>View Shop</h1>
            </div>
        <div class="shop-info">
            <div class="shop-item">
            <p><strong>Shop Name:</strong> <?php echo htmlspecialchars($shop['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($shop['email']); ?></p>
            </div>
            <div class="shop-item"><p><strong>Contact:</strong> <?php echo htmlspecialchars($shop['contact']); ?></p>
            <p><strong>Information:</strong> <?php echo !empty($shop['info']) ? htmlspecialchars($shop['info']) : 'No information available'; ?></p></div>
        </div>
        <div class="shop-images">
        <h1>View Shop</h1>
        <div class="img-container">
        <div class="img-item">
            <p><strong>Profile Picture:</strong></p>
            <img src="../Image/<?php echo htmlspecialchars($shop['profilePic']); ?>" alt="Profile Picture" width="200" height="200">
            </div>
            <div class="img-item"><p><strong>Permit:</strong></p>
            <img src="../Image/<?php echo htmlspecialchars($shop['permit']); ?>" alt="Permit Image" width="200" height="200"></div>
        </div>
            
        </div>

        <div class="form-button">
<!-- Approval and Rejection Forms -->
            <form method="post" style="display:inline;">
            <button type="submit" name="approve" class="approve-button">Approve</button>
            <button type="submit" name="reject" class="reject-button">Reject</button>
            <a href="ShopInfo.php" class="back-button">Back to Shop List</a>
        </form>
        
    </div>
</body>
</html>
