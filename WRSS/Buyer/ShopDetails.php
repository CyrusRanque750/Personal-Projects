<?php
require '../Include/connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
$reviewResponse = ''; // Initialize review response message
$shopId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch shop information
$query = "SELECT * FROM shopinfo WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $shopId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Shop not found!";
    exit();
}

$shop = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$total = 0;
$amount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['review_form'])) {
    // Order form submission logic
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
    $price = isset($shop['price']) ? (float)$shop['price'] : 0;
    $method = isset($_POST['method']) ? $_POST['method'] : '';
    $discount = isset($_POST['discount']) ? (float)$_POST['discount'] : (float)$shop['discount'];
    $deliveryFee = isset($shop['deliveryFee']) ? (float)$shop['deliveryFee'] : 0;

    if (empty($method) || $method === '#' || !in_array($method, ['delivery', 'pickUp'])) {
        $error = "Please select a valid method (Delivery or Pick Up)";
    } elseif ($amount <= 0) {
        $error = "Amount must be a positive number.";
    } else {
        $total = ($amount * $price) - $discount + $deliveryFee;

        $name = $shop['name'];
        if (empty($name)) {
            $error = "Shop name is empty!";
        } else {
            // Insert order code here
        }
        
        $email = $shop['email'];
        $contact = $shop['contact'];
        $info = $shop['info'];
        $profilePic = $shop['profilePic'];
        $orderIndex = 0; 
        $buyerId = 1; 
        $sellerId = $shop['seller_id'];
        $address = $shop['address']; 

        $insertQuery = "INSERT INTO orders (buyer_id, seller_id, name, email, contact, info, profilePic, method, amount, total, discount, address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, 'iissisisidds', $buyerId, $sellerId, $name, $email, $contact, $info, $profilePic, $method, $amount, $total, $discount, $address);
        
        if (mysqli_stmt_execute($stmt)) {
            $error = "Order placed successfully!";
        } else {
            $error = "Error placing order: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
    }
}

// Review form submission logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_form'])) {
    $review = isset($_POST['review']) ? trim($_POST['review']) : '';

    if (!empty($review)) {
        $reviewQuery = "INSERT INTO reviews (shop_id, review_text) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $reviewQuery);
        mysqli_stmt_bind_param($stmt, 'is', $shopId, $review);

        if (mysqli_stmt_execute($stmt)) {
            $reviewResponse = "Review submitted successfully!";
        } else {
            $reviewResponse = "Error submitting review: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $reviewResponse = "Review cannot be empty.";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($shop['name']); ?> - Shop Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@533&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/DetailsShop.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
    <path fill="#0099ff" fill-opacity="1" d="M0,96L13.3,112C26.7,128,53,160,80,181.3C106.7,203,133,213,160,218.7C186.7,224,213,224,240,186.7C266.7,149,293,75,320,69.3C346.7,64,373,128,400,176C426.7,224,453,256,480,272C506.7,288,533,288,560,250.7C586.7,213,613,139,640,122.7C666.7,107,693,149,720,186.7C746.7,224,773,256,800,250.7C826.7,245,853,203,880,197.3C906.7,192,933,224,960,202.7C986.7,181,1013,107,1040,85.3C1066.7,64,1093,96,1120,90.7C1146.7,85,1173,43,1200,48C1226.7,53,1253,107,1280,144C1306.7,181,1333,203,1360,224C1386.7,245,1413,267,1427,277.3L1440,288L1440,0L1426.7,0C1413.3,0,1387,0,1360,0C1333.3,0,1307,0,1280,0C1253.3,0,1227,0,1200,0C1173.3,0,1147,0,1120,0C1093.3,0,1067,0,1040,0C1013.3,0,987,0,960,0C933.3,0,907,0,880,0C853.3,0,827,0,800,0C773.3,0,747,0,720,0C693.3,0,667,0,640,0C613.3,0,587,0,560,0C533.3,0,507,0,480,0C453.3,0,427,0,400,0C373.3,0,347,0,320,0C293.3,0,267,0,240,0C213.3,0,187,0,160,0C133.3,0,107,0,80,0C53.3,0,27,0,13,0L0,0Z"></path>
</svg>
<nav>
    <ul class="home-ul">
        <li class="logo"><a href="FrontPage.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a></li>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <div class="nav-center">
            <h2 class="admin">BUYER</h2>
        </div>
        <li class="nav-button"><a href="../FrontPage.php">Logout</a></li>
    </ul>
</nav>

<!-- Information -->    
<div class="content-container">
    <div class="site-title">
       <h1>Shop Details</h1>
       <a href='AfterSearch.php' class="go-back">Go back</a>
    </div>
    <div class="shop-name">
        <h1>Shop Name: <?php echo htmlspecialchars($shop['name']); ?></h1>
    </div>
    <div class="inside-container">
            <div class="left-div">
                 <img src="../Image/<?php echo htmlspecialchars($shop['profilePic']); ?>" width="150" height="150" alt="Profile Picture">
            </div>
            <div class="middle-div">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($shop['email']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($shop['contact']); ?></p>
                <p><strong>About:</strong> <?php echo !empty($shop['info']) ? htmlspecialchars($shop['info']) : 'No information available'; ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($shop['address']); ?></p>
            </div>
            <div class="right-div">
                <p><strong>Price: ₱</strong> <?php echo htmlspecialchars($shop['price']); ?></p>
                <p><strong>Delivery Fee: ₱</strong> <?php echo htmlspecialchars($shop['deliveryFee']); ?></p>
                <p><strong>Discount: ₱</strong> <?php echo htmlspecialchars($shop['discount']); ?></p>
            </div>
        </div>

    <div id="map"></div>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([0, 0], 15); 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Geocode the address using Nominatim API
        var address = "<?php echo htmlspecialchars($shop['address']); ?>";
        var nominatimURL = "https://nominatim.openstreetmap.org/search?format=json&limit=1&q=" + encodeURIComponent(address);

        // Fetch the geocoded location from Nominatim
        fetch(nominatimURL)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var latitude = data[0].lat;
                    var longitude = data[0].lon;
                    
                    // Set map view to the location and add a marker
                    map.setView([latitude, longitude], 15);
                    L.marker([latitude, longitude]).addTo(map)
                        .bindPopup("<strong><?php echo htmlspecialchars($shop['name']); ?></strong><br>" + address)
                        .openPopup();
                } else {
                    alert("Location not found for the address.");
                }
            })
            .catch(error => {
                console.error("Error fetching location data:", error);
            });
    </script>

        <form method="POST">
                    <input type="hidden" name="shop_id" value="<?php echo $shopId; ?>">
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <label for="method">Select Method:</label>
                    <select name="method" id="method" required>
                        <option value="#">Select Method</option>
                        <option value="pickUp">Pick Up</option>
                        <option value="delivery">Delivery</option>
                    </select>
                    <br>
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" id="amount" min="1" required>
                    <br>
                    <label for="discount">Discount:</label>
                    <input type="number" name="discount" id="discount" value="<?php echo htmlspecialchars($shop['discount']); ?>" step="0.01">
                    <br>
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($shop['address']); ?>" step="0.01">
                    <br>

                    <table class="table-container">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Information</th>
                        <th>Available</th>
                    </tr>
                    <?php
                    $i = 1;
                    $rows = mysqli_query($conn, "SELECT * FROM tb_upload ORDER BY id DESC");

                    foreach ($rows as $row) : ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td>
                            <img src="../Image/<?php echo htmlspecialchars($row["image"]); ?>" alt="Image" width="100" height="100">
                        </td>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo !empty($row["info"]) ? htmlspecialchars($row["info"]) : 'No information available'; ?></td>
                        <td><?php echo htmlspecialchars($row["availability_status"]); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                    <button type="submit">Place Order</button>
                    <br>
                    <p class="total">Total: <span> <?php echo htmlspecialchars($total)?></span></p>
                </form>
    
                <form action="ShopDetails.php?id=<?php echo $shopId; ?>" method="post">
                <input type="hidden" name="review_form" value="1"> <!-- Required for review form -->
                    <div class="bottom-container">
                    <div class="site-title">
                        <h1>Reviews</h1>
                    </div>
                    <div class="review">
                    <div class="form-group">
                        <input class="custom-input text-area" placeholder=" " type="text" name="review" id="review" required>
                        <label for="review">Enter Info</label>
                        <div class="bottom  ">
                            <button type="submit" class="button">Submit Review</button>
                            <a href="Reviews.php" class="view">View</a>
                        </div>
                    </div>
                    </div>
                    </div>
                </form>
<div>
    <p class="response"><?php echo htmlspecialchars($review ?? ''); ?></p>
</div>

</div>

</body>
</html>
 