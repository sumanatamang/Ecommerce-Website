<?php
require "config/constants.php";
session_start();
if (isset($_SESSION["uid"])) {
    header("location:profile.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Achaar Bazar - Contact Us</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="wait overlay">
        <div class="loader"></div>
    </div>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand">Achaar Bazar</a>
            </div>
            <div class="collapse navbar-collapse" id="collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                    <li><a href="#products"><span class="glyphicon glyphicon-modal-window"></span> Pickles</a></li>
                    <li><a href="about.php"><span class="glyphicon glyphicon-info-sign"></span> About Us</a></li>
                    <li class="active"><a href="contact.php"><span class="glyphicon glyphicon-envelope"></span>
                            Contact Us</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="container" style="margin-top:100px; max-width:600px;">
        <h2 class="text-center">Contact Us</h2>
        <p class="text-center" style="margin-bottom:30px;">Have questions or feedback? We'd love to hear from you.</p>
        <form id="contact_form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" rows="5" placeholder="Your Message" required></textarea>
            </div>
            <button type="submit" class="btn btn-warning btn-block">Send Message</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $("#contact_form").on("submit", function (e) {
                e.preventDefault();
                alert("Thank you! Your message has been sent.");
                $("#contact_form")[0].reset();
            });
        });
    </script>

    <footer class="sectionp1" style="margin-top:50px; padding:30px; background:#222; color:white;">
        <div class="col">
            <h4>Contact</h4>
            <p><strong>Address:</strong> Durbarmarg, Kathmandu, Nepal</p>
            <p><strong>Phone:</strong> +9779866888838 / +9779842488838</p>
            <p><strong>Hours:</strong> 10:00am - 6:00pm, Mon - Sat</p>
            <div class="follow">
                <h4>Follow Us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="about.php">About Us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Condition</a>
            <a href="contact.php">Contact Us</a>
        </div>

        <div class="col">
            <h4>Account</h4>
            <a href="login_form.php">Sign In</a>
            <a href="cart.php">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
            <h4>Install App</h4>
            <p>From App Store or Google Play</p>
            <div class="row">
                <img src="./images/appstore.jpg" width="150" height="50" alt="">
                <img src="./images/googleplay.jpg" width="150" height="50" alt="">
            </div>
        </div>

        <div class="copyright">
            &copy; <?php echo date("Y"); ?> Achaar Bazar. All Rights Reserved.
        </div>
    </footer>

</body>

</html>