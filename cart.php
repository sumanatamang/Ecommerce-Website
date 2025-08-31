<?php
require "config/constants.php";
session_start();

if (!isset($_SESSION["uid"])) {
    header("location:index.php"); // redirect if not logged in
    exit();
}

// Database connection
$con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}
$uid = $_SESSION["uid"];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cart - Ecommerce</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
</head>

<body>
    <div class="wait overlay">
        <div class="loader"></div>
    </div>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="profile.php" class="navbar-brand">Ecommerce</a>
            </div>
        </div>
    </div>

    <p><br /><br /><br /></p>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">Cart Checkout</div>
                    <div class="panel-body">

                        <!-- Cart Table Header -->
                        <div class="row">
                            <div class="col-md-2 col-xs-2"><b>Action</b></div>
                            <div class="col-md-2 col-xs-2"><b>Product Image</b></div>
                            <div class="col-md-2 col-xs-2"><b>Product Name</b></div>
                            <div class="col-md-2 col-xs-2"><b>Quantity</b></div>
                            <div class="col-md-2 col-xs-2"><b>Product Price</b></div>
                            <div class="col-md-2 col-xs-2"><b>Total Price</b></div>
                        </div>

                        <!-- Cart Items -->
                        <?php
                        $stmt = $con->prepare("SELECT c.id, c.qty, p.product_id, p.product_title, p.product_price, p.product_image 
                                           FROM cart c 
                                           JOIN products p ON c.p_id = p.product_id 
                                           WHERE c.user_id=?");
                        $stmt->bind_param("i", $uid);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $grand_total = 0;

                        while ($row = $result->fetch_assoc()) {
                            $total_price = $row['product_price'] * $row['qty'];
                            $grand_total += $total_price;
                            echo '
                        <div class="row" style="margin-bottom:15px;">
                            <div class="col-md-2 col-xs-2">
                                <button class="btn btn-danger btn-xs removeItem" pid="' . $row['product_id'] . '">X</button>
                            </div>
                            <div class="col-md-2 col-xs-2">
                                <img src="product_images/' . $row['product_image'] . '" style="width:50px; height:50px;">
                            </div>
                            <div class="col-md-2 col-xs-2">' . $row['product_title'] . '</div>
                            <div class="col-md-2 col-xs-2">
                                <input type="number" class="form-control qty" value="' . $row['qty'] . '" min="1" pid="' . $row['product_id'] . '">
                            </div>
                            <div class="col-md-2 col-xs-2">' . CURRENCY . $row['product_price'] . '</div>
                            <div class="col-md-2 col-xs-2">' . CURRENCY . $total_price . '</div>
                        </div>
                        ';
                        }

                        if ($grand_total > 0) {
                            echo '
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <h4>Grand Total: ' . CURRENCY . $grand_total . '</h4>
                                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                            </div>
                        </div>
                        ';
                        } else {
                            echo '<h4 class="text-center">Your cart is empty!</h4>';
                        }

                        $stmt->close();
                        ?>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </div>

            <div class="col-md-2"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            // Remove item from cart
            $(".removeItem").click(function () {
                var pid = $(this).attr("pid");
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data: { removeFromCart: 1, proId: pid },
                    success: function (response) {
                        alert(response);
                        location.reload(); // reload cart page to reflect changes
                    }
                });
            });

            // Update quantity
            $(".qty").change(function () {
                var pid = $(this).attr("pid");
                var qty = $(this).val();
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data: { updateCart: 1, proId: pid, qty: qty },
                    success: function (response) {
                        alert(response);
                        location.reload(); // reload cart page to reflect changes
                    }
                });
            });

        });
    </script>
</body>

</html>