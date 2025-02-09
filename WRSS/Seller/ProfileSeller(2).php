<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "waterstation";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user ID exists in the session
if (!isset($_SESSION['id'])) {
    echo "User ID not set in session. Please log in.";
    exit;
}

$user_id = $_SESSION['id'];  // The seller's user ID
$email = $_SESSION['email']; // Retrieve the email from session

// Initialize profile with default values
$profile = [
    'full_name' => 'N/A',
    'email' => $email,
    'contact' => 'N/A',
    'telephone' => 'N/A',
    'address' => 'N/A',
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data from POST request
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $address = $conn->real_escape_string($_POST['address']);

    

$query = "INSERT INTO seller_profiles (user_id, full_name, contact, email, telephone, address) 
VALUES ('$user_id', '$full_name', '$contact', '$email', '$telephone', '$address')
ON DUPLICATE KEY UPDATE full_name='$full_name', contact='$contact', email='$email',telephone='$telephone', address='$address'";


    $conn->query($query);

    // Reload the page to display the updated profile info
    header("Location: ProfileSeller.php");
    exit;
}

// Fetch profile data for display in info-container
$query = "SELECT full_name, email, contact, telephone, address FROM seller_profiles WHERE user_id = '$user_id'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    // Populate $profile with data from the database
    $profile = $result->fetch_assoc();
}


// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@533&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/ProfileSeller.css">
    <script src="../Script/NavBar.js" async></script>
    <script src="../Script/Collapsible.js" async></script>
    <title>After Search</title>
    <style>
        .modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
}
.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    position: relative;
}
.close-button {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 18px;
    cursor: pointer;
}
.modal form label {
    display: block;
    margin: 10px 0 5px;
}
.modal form input {
    width: 100%;
    padding: 8px;
    /* margin-bottom: 10px; */
    border: 1px solid #ddd;
    border-radius: 4px;
}

    </style>
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
                <li class="nav-links"><a href="#about">SELLER</a></li>
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
            </div>
        </ul>
    </div>

   
<!-- Seller's Information -->
<div class="container">
        <div class="child-container">
            <h3>Seller's Information</h3>
            <div class="img-container">
                <img src="../Image/UserIcon.jpg" alt="User Icon">
            </div>
            <div class="info-container">
                <h4>Owner's Name:</h4>
                <p><?php echo htmlspecialchars($profile['full_name']); ?></p>
                
                <h4>Contact Number:</h4>
                <p><?php echo htmlspecialchars($profile['contact']); ?></p>
                
                <h4>Telephone Number:</h4>
                <p><?php echo htmlspecialchars($profile['telephone']); ?></p>
                
                <h4>Email Address:</h4>
                <p><?php echo htmlspecialchars($profile['email']); ?></p>
                
                <h4>Station Address:</h4>
                <p><?php echo htmlspecialchars($profile['address']); ?></p>
                
                <!-- Edit Button to open modal -->
                <button onclick="openModal()" class="edit-button">Edit Information</button>
            </div>
        </div>
    
            <div class="employee-container">
                <h3>Employee</h3>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Availability</th>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Joniel Gesta</td>
                            <td>09212103186</td>
                            <td>
                                <button class="button-yes">Yes</button>
                                <button class="button-no">No</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
</body>

 <!-- Modal for Editing Seller Information -->
 <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2>Edit Seller Information</h2>
            <form method="post" action="ProfileSeller.php">
                <label>Owner's Name:</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($profile['full_name']); ?>">

                <label>Contact Number:</label>
                <input type="text" name="contact" value="<?php echo htmlspecialchars($profile['contact']); ?>">

                <label>Telephone Number:</label>
                <input type="text" name="telephone" value="<?php echo htmlspecialchars($profile['telephone']); ?>">

                <label>Station Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($profile['address']); ?>">

                <button type="submit" class="save-button">Save Changes</button>
            </form>
        </div>
    </div>
<script>
    function toggleMenu() {
    document.querySelector('.home-ul').classList.toggle('active');
}
        function openModal() {
            document.getElementById("editProfileModal").style.display = "block";
        }

        // Function to close modal
        function closeModal() {
            document.getElementById("editProfileModal").style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById("editProfileModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
</script>
</body>
</html>




  