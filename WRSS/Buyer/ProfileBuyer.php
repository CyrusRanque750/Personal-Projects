<?php
require "../Include/connect.php";
session_start();

// Ensure user ID exists in the session
if (!isset($_SESSION['id'])) {
    echo "User ID not set in session. Please log in.";
    exit;
}

$user_id = $_SESSION['id'];
$email = $_SESSION['email'];

// $email = isset($_SESSION['email']) ? $_SESSION['email'] : 'N/A'; 
// $user_id = $_SESSION['id']; 

// Initialize profile with default values
$profile = [
    'full_name' => 'N/A',
    'email' => $email,
    'contact' => 'N/A',
    'address' => 'N/A',
];

// Handle form submission to update profile data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    
    // Insert or update profile data in the database
    $query = "INSERT INTO user_profiles (user_id, full_name, email, contact, address) 
              VALUES ('$user_id', '$full_name', '$email', '$contact', '$address')
              ON DUPLICATE KEY UPDATE full_name='$full_name', email='$email', contact='$contact', address='$address'";

    $conn->query($query);

    // Reload the page to display the updated profile info
    header("Location: ProfileBuyer.php");
    exit;
}

// Fetch profile data for display in profile-info based on user_id
$query = "SELECT full_name, email, contact, address FROM user_profiles WHERE user_id = '$user_id'";
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
    <title>Buyer Profile</title>
    <link rel="stylesheet" href="../Css/ProfileBuyer.css">

    <style>
        /* Modal */

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

.save {
    margin-top: 10px;
    padding: 5px 10px;
    background-color: #0099ff;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}

#id {
    width: 150px;
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
                <li class="nav-links"><a href="#about">About</a></li>
                <li class="nav-links"><a href="#services">Services</a></li>
                <li class="nav-links"><a href="#contacts">Contact Us</a></li>
            </div>
            <li class="nav-button"><a href="">Logout</a></li>
        </ul>
    </nav>
    <div class="profile-container">
        <div class="profile-info">
            <div class="profile-header">
            <img src="" alt="Profile Picture" class="profile-picture">
                <h2>Welcome, <?php echo htmlspecialchars($profile['full_name']); ?></h2>
                <button onclick="openModal()" class="edit-profile-button">Edit Profile</button>
            </div>
    
            <div class="profile-details">
            <p><strong>Full Name:</strong></p>
            <p><?php echo htmlspecialchars($profile['full_name']); ?></p>
                <p><strong>Email:</strong> </p>
                <p><?php echo htmlspecialchars($profile['email']); ?></p>
                <p><strong>Contact Number:</strong> </p>
                <p><?php echo htmlspecialchars($profile['contact']); ?></p>
                <p><strong>Address:</strong> </p>
                <p><?php echo htmlspecialchars($profile['address']); ?></p>
            </div>
        </div>
        

        <div class="order-history">
            <h3>Order History</h3>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>12345</td>
                    <td>Oct 15, 2024</td>
                    <td>10</td>
                    <td>Delivered</td>
                    <td>₱1,500</td>
                </tr>
                <tr>
                    <td>12346</td>
                    <td>Oct 17, 2024</td>
                    <td>12</td>
                    <td>Processing</td>
                    <td>₱1,200</td>
                </tr>
            </table>
            <div class="action-buttons">
                <button class="order-button">New Order</button>
                <button class="logout-button">Logout</button>
            </div>
        </div>
    </div>

    <div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Edit Profile</h2>
        <form method="post" action="ProfileBuyer.php">
                <label>Full Name:</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($profile['full_name']); ?>">
                
                <!-- <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>"> -->
                
                <label>Contact Number:</label>
                <input type="text" name="contact" value="<?php echo htmlspecialchars($profile['contact']); ?>">
                
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($profile['address']); ?>">
                
                <button type="submit" class="save">Save</button>
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

    // Close the modal
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


