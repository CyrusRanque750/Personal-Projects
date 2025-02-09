<?php
session_start();
require_once '../Include/connect.php';

$shops = [];

// Only execute search if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usersearch = htmlspecialchars($_POST["usersearch"]);

    // Prepare the SQL query to search for the shop
    $query = "SELECT * FROM shopinfo WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usersearch);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any results
    if ($result && $result->num_rows > 0) {
        $shops = $result->fetch_all(MYSQLI_ASSOC); // Store results in an array
    } else {
        echo "<p>No results found for your search.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: Search.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRSS Search Results</title>
    <link rel="stylesheet" href="../Css/afterSearch.css">
</head>
<body>
    <nav>
        <ul class="home-ul">
            <li class="logo"><a href="../FrontPage.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a></li>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <li class="nav-button"><a href="AllShops.php">Shops</a></li>
            <li class="nav-button"><a href="../FrontPage.php">Logout</a></li>
        </ul>
    </nav>
    
    <section>
        <h3>Search Results:</h3>
        <button class="back"><a href='Search.php'>Go back</a></button>

        <?php if (!empty($shops)): ?>
            <?php foreach ($shops as $shop): ?>
                <a href="ShopDetails.php?id=<?php echo urlencode($shop['id']); ?>" class="container-shop">
                    <div class='container'>
                        <!-- Display the profile picture -->
                        <?php
                        $imageDirectory = '../Image/';
                        $imagePath = $imageDirectory . htmlspecialchars($shop["profilePic"]);
                        ?>
                        <div class='right-div'>
                            <p><strong>Profile Picture:</strong></p>
                            <?php if (!empty($shop["profilePic"]) && file_exists($imagePath)): ?>
                                <img src="<?php echo $imagePath; ?>" alt="Profile Picture" style="width:100px;height:auto;"/>
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                        </div>

                        <!-- Shop details -->
                        <div class='left-div'>
                            <div><strong>Shop Name:</strong> <?php echo htmlspecialchars($shop["name"]); ?></div>
                            <div><strong>Email:</strong> <?php echo htmlspecialchars($shop["email"]); ?></div>
                            <div><strong>Contact:</strong> <?php echo htmlspecialchars($shop["contact"]); ?></div>
                            <div><strong>About:</strong> <?php echo htmlspecialchars($shop["info"]); ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </section>

    <script>
        function toggleMenu() {
            document.querySelector('.home-ul').classList.toggle('active');
        }
    </script>
</body>
</html>
