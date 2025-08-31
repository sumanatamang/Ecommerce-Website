<?php
session_start();
include "constants.php";

// Redirect if user not logged in
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Achaar Bazar</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            height: 100%;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 10px;
        }
        .sidebar {
            margin-top: 20px;
        }
        .product-title {
            font-size: 16px;
            font-weight: bold;
            min-height: 40px;
        }
        .product-price {
            font-size: 14px;
            color: #28a745;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo isset($_SESSION['uid']) ? 'profile.php' : 'index.php'; ?>">Achaar Bazar</a>

        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="profile.php">Profile</a></li>
                <li><a href="cart.php">Cart <span id="cart-count" class="badge">0</span></a></li>
            </ul>

            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search Pickles..." id="search">
                </div>
                <button type="submit" class="btn btn-success" id="search_btn">Search</button>
            </form>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-user"></span> Account <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" style="width:250px; padding:10px;">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Hello, <?php echo htmlspecialchars($_SESSION["name"]); ?></div>
                            <div class="panel-body">
                                <p><a href="profile.php" class="btn btn-default btn-block">My Profile</a></p>
                                <p><a href="logout.php" class="btn btn-warning btn-block">Logout</a></p>
                            </div>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Container -->
<div class="container-fluid" style="margin-top:70px;">
    <div class="row">
        <!-- Sidebar: Categories -->
        <div class="col-md-3 sidebar">
            <h4>Categories</h4>
            <ul class="list-group">
                <?php
                $cat_query = "SELECT * FROM categories";
                $result = $con->query($cat_query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li class='list-group-item'>
                                <a href='profile.php?cat_id=" . $row['cat_id'] . "'>" . $row['cat_title'] . "</a>
                              </li>";
                    }
                } else {
                    echo "<li class='list-group-item'>No categories</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h3>
            <p>Browse products, add to cart, and manage your orders below.</p>

            <div class="row">
                <?php
                $product_query = "SELECT * FROM products";
                if (isset($_GET['cat_id'])) {
                    $cat_id = (int)$_GET['cat_id'];
                    $product_query .= " WHERE product_cat = $cat_id";
                }
                $result = $con->query($product_query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='col-sm-6 col-md-4'>
                            <div class='card'>
                                <img src='product_images/" . $row['product_image'] . "' class='card-img-top' alt='" . $row['product_title'] . "'>
                                <div class='card-body'>
                                    <div class='product-title'>" . $row['product_title'] . "</div>
                                    <div class='product-price'>" . CURRENCY . " " . $row['product_price'] . "</div>
                                    <a href='cart.php?add=" . $row['product_id'] . "' class='btn btn-primary btn-sm btn-block'>Add to Cart</a>
                                </div>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    echo "<div class='col-md-12'><p>No products found.</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="margin-top:50px; padding:30px; background:#222; color:white;">
    &copy; <?php echo date("Y"); ?> Achaar Bazar. All Rights Reserved.
</footer>

<script src="js/jquery2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="main.js"></script>
</body>
</html>
