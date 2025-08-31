<?php
require "config/constants.php";
session_start();
if (!isset($_SESSION["uid"])) {
    header("location:index.php");
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
            max-width: 1200px;
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

        /* Sidebar Categories */
        .category-list {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .category-list li {
            padding: 12px 15px;
            border-bottom: 1px solid #ccc;
        }

        .category-list li button {
            width: 100%;
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

                <form class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search Pickles" id="search">
                    </div>
                    <button type="button" class="btn btn-success" id="search_btn"><span
                            class="glyphicon glyphicon-search"></span></button>
                </form>

                <ul class="nav navbar-nav navbar-right">
                    <!-- Single Cart Link -->
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
        <div class="row">

            <!-- Categories Sidebar -->
            <div class="col-md-2">
                <ul class="category-list" id="get_category"></ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">

                <!-- Hero Section -->
                <section id="hero" class="hero">
                    <div class="hero-container">
                        <div class="hero-text">
                            <h1>Welcome to Achaar Bazar</h1>
                            <p>Authentic Nepali Pickles Made with Love</p>
                            <p>Healthy pickles made with fresh ingredients and traditional recipes. Perfect for any
                                meal.</p>
                            <a href="#products" class="btn btn-warning btn-lg">Shop Now</a>
                        </div>
                        <div class="hero-image">
                            <img src="./product_images/banner.jpg" alt="Nepali Pickles">
                        </div>
                    </div>
                </section>

                <!-- Products -->
                <div class="row" id="product_msg"></div>
                <div class="panel panel-info" id="scroll">
                    <div class="panel-heading">Our Pickles</div>
                    <div class="panel-body" id="get_product"></div>
                    <div class="panel-footer" style="margin-top:50px; padding:30px; background:#ffc107; color:white;">
                        &copy; <?php echo date("Y"); ?> Achaar Bazar. All Rights Reserved.</div>

                </div>
            </div>

            <div class="col-md-2"></div>
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

        // Load categories stacked
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
                        $('#get_category').append('<li><button class="btn btn-success category mb-2" cid="' + cid + '">' + title + '</button></li>');
                    });
                }
            });
        });
    </script>

</body>

</html>