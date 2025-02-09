<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <title>Water Refilling System</title>
</head>

<body>
    <main>
        <section class="home" id="home">
            <!--- The Navigation -->
            <nav>
                <ul class="home-ul">
                   <a href="FrontPage.php"><img src="Image/Thirstdrop.png" alt="" height="30" width="30" class="logo"></a>
                    <span class="menu-toggle" onclick="toggleMenu()">☰</span>
                    <div class="nav-center">
                        <li class="nav-links"><a href="#about">About</a></li>
                        <li class="nav-links"><a href="#services">Services</a></li>
                        <li class="nav-links"><a href="#contacts">Contact Us</a></li>
                    </div>
                    <li class="nav-button"><a href="User-Buyer.php">Register</a></li>
                    <li class="nav-button"><a href="Login.php">Login</a></li>
                </ul>
            </nav>
            <div class="home-container">
                <div class="child1-container">
                    <h1 class="home-h1">A <span>Water A Day</span>, Keeps <span>Doctor Away</span></h1>

                    <div class="home-item">
                        <i class="fi fi-ts-shipping-fast"></i>
                        <p style="padding-left: 20px; font-size: 3vh;">Fast Delivery</p>
                    </div>
                    <div class="home-item">
                        <i class="fi fi-ts-hand-holding-water"></i>
                        <p style="padding-left: 20px; font-size: 3vh;">Certified Environment Friendly</p>
                    </div>
                    <div class="home-item">
                        <i class="fi fi-tr-smile-beam"></i>
                        <p style="padding-left: 20px; font-size: 3vh;">Friendly Costumer Service</p>
                    </div>

                </div>
                <div class="child2-container">
                    <img class="home-splash" src="Image/Watersplash.png" alt="">
                </div>
            </div>
        </section>
        <section class="about" id="about">
            <header class="about-header">
                <h1>#1 Environmental Healthy Water in the Philippines</h1>
            </header>
            <div class="about-container">
                <div class="div1">
                    <h1>About</h1>
                    <p>The purpose of this system is to cater the concerns of the citizen regarding about how difficult it is to find a water station especially as a person who recently move to a residency.</p>
                    <p>It also makes the life of people more convenient in a sense that they do not need to find a
                        people who needs to carry a gallon of water particularly to those people who has an automatic
                        water machine business.</p>
                </div>
                <div class="div2">
                    <img src="Image/deliveryman.webp" alt="">
                </div>
            </div>
        </section>
        <br>
        <br>
        <section class="services" id="services">
            <h1>Services</h1>
            <div class="services-container">
                <div class="content">
                    <h4>Fast Delivery</h4>
                    <p>Your product will be delivered fast and secure.</p>
                    <h4>Friendly Customer Services</h4>
                    <p>We assure you that our service are reliable and friendly.</p>
                    <h4>Clean Product</h4>
                    <p>We also guarantee that the product is clean and well purified.</p>
                </div>
                <div class="img-container">
                    <img src="Image/gallon.png" alt="">
                </div>
            </div>

        </section>
        <footer id="contacts">
            <p class="ready">We are ready anytime for you</p>
            <div class="footer-container">
                <div class="follow-us">
                    <h4>Follow us</h4>
                    <i class="fa-brands fa-facebook-messenger fa-2x" style="color: #01497c;"></i>
                    <i class="fa-brands fa-facebook fa-2x" style="color: #01497c;"></i>
                    <i class="fa-brands fa-square-twitter fa-2x" style="color: #01497c;"></i>
                    <i class="fa-brands fa-instagram fa-2x" style="color: #01497c;"></i>
                </div>
                <div class="contact-us">
                    <h4>Contact Us</h4>
                    <div class="contact-item">
                        <i class="fa-regular fa-envelope fa-1xl" style="color: #01497c;"></i>
                        <p>wrss@gmail.com</p>
                    </div>
                    <div class="contact-item">
                        <i class="fa-solid fa-phone fa-1xl" style="color: #01497c;"></i>
                        <p>0932 456 1259</p>
                    </div>
                </div>
                <div class="quick-links">
                    <ul class="contact-ul">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#contacts">Contacts</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </main>
</body>
<script>
    function toggleMenu() {
    document.querySelector('.home-ul').classList.toggle('active');
}
</script>
</html>