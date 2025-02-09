
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WRSS Search</title>
    <link rel="stylesheet" href="../Css/Search.css">
</head>
<body>
    <nav>
        <ul class="home-ul">
            <li class="logo"><a href="../FrontPage.php"><img src="../Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a></li>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <li class="nav-button"><a href="AllShops.php">Shops</a></li>
            <li class="nav-button"><a href="../FrontPage.php">Logout</a></li>
            <li><a href="ProfileBuyer.php"><img src="../Image/UserIcon.jpg" alt="" height="40" width="40" class="logo"></a></li>
        </ul>
    </nav>

    <main>
        <form action="AfterSearch.php" method="post">
            <h1>Looking for <span>location</span>?</h1>
            <div class="search-box">
                <input type="text" name="usersearch" placeholder="Search..." required>
                <button type="submit">Search</button>
            </div>
        </form>
    </main>
    
    <footer>
        <section class="common-locations">
            <h2>Common Locations</h2>
            <div class="locations-grid">
                <div class="location-card"><div>Cebu City</div></div>
                <div class="location-card"><div>Brgy. Pahina Central</div></div>
                <div class="location-card"><div>Brgy. Proper Toong</div></div>
                <div class="location-card"><div>Lapu-Lapu City</div></div>
                <div class="location-card"><div>Brgy. Luz</div></div>
                <div class="location-card"><div>Brgy. Kalunasan</div></div>
            </div>
        </section>
    </footer>

    <script>
        function toggleMenu() {
            document.querySelector('.home-ul').classList.toggle('active');
        }
    </script>
</body>
</html>
