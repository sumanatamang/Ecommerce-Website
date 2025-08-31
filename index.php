<?php
require "config/constants.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Achaar Bazar - Nepali Pickles</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                <span class="sr-only">navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.php" class="navbar-brand">Achaar Bazar</a>
        </div>
        <div class="collapse navbar-collapse" id="collapse">
            <ul class="nav navbar-nav">
                <li><a href="#hero"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li><a href="#products"><span class="glyphicon glyphicon-modal-window"></span> Pickles</a></li>
                <li><a href="about.php"><span class="glyphicon glyphicon-info-sign"></span> About Us</a></li>
                <li><a href="contact.php"><span class="glyphicon glyphicon-envelope"></span> Contact</a></li>
            </ul>
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search Pickles..." id="search">
                </div>
                <button type="submit" class="btn btn-success" id="search_btn">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-shopping-cart"></span> Cart <span class="badge">0</span>
                    </a>
                    <div class="dropdown-menu" style="width:400px;">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-3">Sl.No</div>
                                    <div class="col-md-3">Image</div>
                                    <div class="col-md-3">Pickle Name</div>
                                    <div class="col-md-3">Price (<?php echo CURRENCY; ?>)</div>
                                </div>
                            </div>
                            <div class="panel-body" id="cart_product"></div>
                            <div class="panel-footer"></div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-user"></span> Login/Register
                    </a>
                    <ul class="dropdown-menu">
                        <div style="width:300px;">
                            <div class="panel panel-primary">
                                <div class="panel-heading">Customer Login</div>
                                <div class="panel-heading">
                                    <form onsubmit="return false" id="login">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" required/>
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required/>
                                        <p><br/></p>
                                        <input type="submit" class="btn btn-warning" value="Login">
                                        <a href="customer_registration.php?register=1" style="color:white; text-decoration:none;">
                                            No Account? Sign Up
                                        </a>
                                    </form>
                                </div>
                                <div class="panel-footer" id="e_msg"></div>
                            </div>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section id="hero" class="hero">
  <div class="hero-container">
    <div class="hero-text">
      <h1>Welcome to Achaar Bazar</h1>
      <p>Authentic Nepali Pickles Made with Love</p>
      <p>Healthy pickles made with fresh ingredients and traditional
         recipes. Our pickles are packed with flavor and nutrients, 
         making them the perfect addition to any meal.</p>
      <a href="#products" class="btn btn-warning btn-lg">Shop Now</a>
    </div>
    <div class="hero-image">
      <img src="./product_images/banner.jpg" alt="Nepali Pickles" />
    </div>
  </div>
</section>


<!-- Featured Categories -->
<div class="container text-center" style="margin-top:50px;">
    <h2>Our Categories</h2>
    <div class="row" id="get_category"></div> <!-- Corrected ID -->
</div>

<hr style="margin: 40px 0; border-top: 2px solid #fff8dc;">

<!-- Products Section -->
<div class="container" id="products" style="margin-top:50px;">
    <h2 class="text-center">Our Pickles</h2>
    <div class="row" id="get_product"></div>
</div>

<!-- Footer -->
<footer class="text-center" style="margin-top:50px; padding:30px; background:#222; color:white;">
    &copy; <?php echo date("Y"); ?> Achaar Bazar. All Rights Reserved.
</footer>

</body>
</html>
