<?php
require "config/constants.php";
session_start();
if (!isset($_SESSION["uid"])) {
    header("location:index.php");
    exit();
}

// Database connection
$con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);
if ($con->connect_error) {
    die("Database Connection Failed: " . $con->connect_error);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Achaar Bazar - Welcome <?php echo $_SESSION['name']; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
    <link rel="stylesheet" href="style.css" />
    <style>
        /* Hero Section */
        .hero {
            color: #222;
            padding: 80px 40px;
            background-color: #fff8dc;
            overflow: hidden;
        }

        .hero-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: auto;
        }

        .hero-text {
            flex: 1;
            max-width: 50%;
            padding-right: 30px;
        }

        .hero-image {
            flex: 1;
            max-width: 50%;
            text-align: right;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
        }

        .hero h1 {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .hero .btn {
            padding: 12px 25px;
            font-size: 16px;
            background: #ffc107;
            border: none;
            color: #222;
            border-radius: 5px;
            transition: 0.3s;
        }

        .hero .btn:hover {
            transform: scale(1.05);
            background: #e0a800;
        }

        /* Categories Row Below Hero */
        #get_category .btn.category {
            margin: 5px;
            min-width: 120px;
        }

        /* Responsive */
        @media (max-width:768px) {
            .hero-container {
                flex-direction: column;
                text-align: center;
            }

            .hero-text,
            .hero-image {
                max-width: 100%;
                padding: 0;
            }

            .hero-image {
                margin-top: 20px;
            }
        }

        @media (max-width:480px) {
            .hero h1 {
                font-size: 35px;
            }

            .hero p {
                font-size: 16px;
            }

            .hero .btn {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="profile.php" class="navbar-brand">Achaar Bazar</a>
            </div>

            <div class="collapse navbar-collapse" id="collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#hero"><span class="glyphicon glyphicon-home"></span> Home </a></li>
                    <li><a href="#products"><span class="glyphicon glyphicon-modal-window"></span> Pickles</a></li>
                    <li><a href="customer_order.php"><span class="glyphicon glyphicon-list-alt"></span> Orders</a></li>
                </ul>

                <form class="navbar-form navbar-left" autocomplete="off">
                    <div class="form-group" style="position:relative;">
                        <input type="text" class="form-control" placeholder="Search Pickles..." id="search">
                        <div id="search_suggestions" class="list-group"
                            style="position:absolute; top:38px; width:100%; z-index:1000;"></div>
                    </div>
                    <button type="submit" class="btn btn-success" id="search_btn">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </form>


                <ul class="nav navbar-nav navbar-right">
                    <!-- Cart Link -->
                    <li>
                        <a href="cart.php">
                            <span class="glyphicon glyphicon-shopping-cart"></span> Cart
                            <span class="badge" id="cart_count">0</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION["name"]; ?> <b
                                class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
                            <li class="divider"></li>
                            <li><a href="customer_order.php">Orders</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <p><br /><br /><br /></p>

    <div class="container-fluid">

        <!-- Hero Section -->
        <section id="hero" class="hero">
            <div class="hero-container">
                <div class="hero-text">
                    <h1>Welcome to Achaar Bazar</h1>
                    <p>Authentic Nepali Pickles Made with Love</p>
                    <p>Healthy pickles made with fresh ingredients and traditional recipes. Perfect for any meal.</p>
                    <a href="#products" class="btn btn-warning btn-lg">Shop Now</a>
                </div>
                <div class="hero-image">
                    <img src="./images/banner.jpg" alt="Nepali Pickles">
                </div>
            </div>
        </section>

        <!-- Categories Row Below Hero -->
        <div class="container text-center" style="margin-top:50px;">
            <h2>Our Categories</h2>
            <div id="get_category" class="d-flex justify-content-center flex-wrap gap-2"></div>
        </div>

        <!-- Products Section -->
        <div class="row" id="product_msg"></div>
        <div class="panel panel-info" id="scroll">
            <div class="panel-heading">Our Pickles</div>
            <div class="panel-body" id="get_product"></div>
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-md-12 text-center">
                <ul class="pagination" id="pageno"></ul>
            </div>
        </div>

    </div>

    <script>
        var CURRENCY = '<?php echo CURRENCY; ?>';

        // Load categories in a horizontal row
        $(document).ready(function () {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { category: 1 },
                success: function (data) {
                    $('#get_category').html('');
                    $(data).find('h5').each(function () {
                        let title = $(this).text();
                        let cid = $(this).next('button').attr('cid');
                        $('#get_category').append('<button class="btn btn-success category" cid="' + cid + '">' + title + '</button>');
                    });
                }
            });
        });
    </script>
    <!-- Footer -->
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