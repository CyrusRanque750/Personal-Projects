<?php
require '../Include/connect.php';

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the shop ID from the URL
$shopId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the shop information, including the address
$query = "SELECT * FROM shopinfo WHERE id = $shopId";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Shop not found!";
    exit();
}

$shop = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($shop['name']); ?> - Shop Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@533&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/ShopDetails.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
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
                <h2 class="admin">ADMIN</h2>
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
            <li><a href="Dashboard.php">Dashboard</a></li>
            <li class="collapsible"><a href="ApprovedShops.php">Approve</a></li>
            <li class="collapsible">Information</li>
            <div class="info">
                <i><a href="#">Orders</a></i>
                <i><a href="ShopInfo.php">Shop</a></i>
                <i><a href="EmployeeInfo.php">Employee</a></i>
                <i><a href="Reviews.php">Reviews</a></i>
            </div>
        </ul>
    </div>

<!-- Information -->    
    <div class="content-container">
        <div class="site-title">
           <h1>Shop Details</h1>
           <a href='AfterSearch.php' class="go-back">Go back</a>
        </div>
        <div class="shop-name">
            <h1><?php echo htmlspecialchars($shop['name']); ?></h1>
        </div>
       <div class="inside-container">
                <div class="left-div">
                     <img src="../Image/<?php echo htmlspecialchars($shop['profilePic']); ?>" width="150" height="150" alt="Profile Picture">
                </div>
                <div class="right-div">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($shop['email']); ?></>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($shop['contact']); ?></p>
                    <p><strong>About:</strong> <?php echo !empty($shop['info']) ? htmlspecialchars($shop['info']) : 'No information available'; ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($shop['address']); ?></p>
                </div>
       </div>
        <!-- Map Container -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Initialize the map with a default view
        var map = L.map('map').setView([0, 0], 15); // Centered at [0, 0] with default zoom level

        // Add the OpenStreetMap tiles
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
   
</body>
</html>
