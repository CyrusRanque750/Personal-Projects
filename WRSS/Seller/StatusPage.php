<?php
// StatusPage.php

$status = $_GET['status'] ?? 'Status not set'; // Default message if no status is passed

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Page</title>
</head>
<body>
    <h1>Status Page</h1>

    <h2>Status: <?php echo htmlspecialchars($status); ?></h2> <!-- Display the status -->

    <a href="ProfileSeller.php">Back to Seller Profile</a> <!-- Link back to the main page -->
</body>
</html>
