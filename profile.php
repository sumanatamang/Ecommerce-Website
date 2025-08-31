<?php
session_start();
include "constants.php";

// Redirect if user not logged in
if(!isset($_SESSION["uid"])){
    header("location:index.php");
    exit();
}

// Database connection
$con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);
if($con->connect_error){
    die("Database Connection Failed: " . $con->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - E-Commerce</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">E-Commerce</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart <span id="cart-count" class="badge badge-warning">0</span></a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Page Layout -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar: Categories -->
            <div class="col-md-3">
                <h5>Categories</h5>
                <ul class="list-group">
                    <?php
                    $cat_query = "SELECT * FROM categories";
                    $result = $con->query($cat_query);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "<li class='list-group-item'>
                                    <a href='profile.php?cat_id=".$row['cat_id']."'>".$row['cat_title']."</a>
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
                <h3>Welcome, <?php echo $_SESSION["name"]; ?>!</h3>
                <p>Here you can browse products, add to cart, and manage your orders.</p>

                <div class="row">
                    <?php
                    // Fetch products
                    $product_query = "SELECT * FROM products";
                    if(isset($_GET['cat_id'])){
                        $cat_id = (int)$_GET['cat_id'];
                        $product_query .= " WHERE product_cat = $cat_id";
                    }

                    $result = $con->query($product_query);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo "
                            <div class='col-md-4 mb-4'>
                                <div class='card h-100'>
                                    <img src='product_images/".$row['product_image']."' class='card-img-top' alt='".$row['product_title']."'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>".$row['product_title']."</h5>
                                        <p class='card-text'>".CURRENCY." ".$row['product_price']."</p>
                                        <a href='cart.php?add=".$row['product_id']."' class='btn btn-primary btn-sm'>Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p>No products found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
