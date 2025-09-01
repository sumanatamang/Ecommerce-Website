<?php
session_start();
if (!isset($_SESSION["uid"])) {
    header("location:index.php");
    exit();
}

if (isset($_GET["st"], $_GET["tx"], $_GET["amt"], $_GET["cc"], $_GET["cm"])) {

    $trx_id = $_GET["tx"];
    $p_st = $_GET["st"];
    $amt = $_GET["amt"];
    $cc = $_GET["cc"];
    $cm_user_id = $_GET["cm"];

    // Only proceed if payment status is Completed
    if ($p_st === "Completed") {

        include_once("db.php");

        // Fetch cart items for the user
        $stmt = $con->prepare("SELECT p_id, qty FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $cm_user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }

            // Insert each cart item into orders table
            $insert_stmt = $con->prepare("INSERT INTO orders (user_id, product_id, qty, trx_id, p_status) VALUES (?, ?, ?, ?, ?)");
            foreach ($orders as $order) {
                $insert_stmt->bind_param("iiiss", $cm_user_id, $order['p_id'], $order['qty'], $trx_id, $p_st);
                $insert_stmt->execute();
            }

            // Delete items from cart
            $delete_stmt = $con->prepare("DELETE FROM cart WHERE user_id = ?");
            $delete_stmt->bind_param("i", $cm_user_id);
            $delete_stmt->execute();

            // Show success page
            ?>
            <!DOCTYPE html>
            <html>

            <head>
                <meta charset="UTF-8">
                <title>Achaar Bazar - Payment Success</title>
                <link rel="stylesheet" href="css/bootstrap.min.css" />
                <script src="js/jquery2.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="main.js"></script>
                <style>
                    body {
                        padding-top: 60px;
                    }

                    .panel-body {
                        padding: 40px;
                    }
                </style>
            </head>

            <body>
                <!-- Navbar -->
                <div class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="#" class="navbar-brand">Achaar Bazar</a>
                        </div>
                        <ul class="nav navbar-nav">
                            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                            <li><a href="profile.php"><span class="glyphicon glyphicon-modal-window"></span> Products</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Payment Success -->
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="panel panel-success">
                                <div class="panel-heading text-center">
                                    <h3>Payment Successful</h3>
                                </div>
                                <div class="panel-body text-center">
                                    <h2>Thank You!</h2>
                                    <p>Hello <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>, your payment has been
                                        successfully completed.</p>
                                    <p>Transaction ID: <b><?php echo htmlspecialchars($trx_id); ?></b></p>
                                    <p>Amount Paid: <b><?php echo htmlspecialchars($amt); ?>
                                            <?php echo htmlspecialchars($cc); ?></b></p>
                                    <a href="index.php" class="btn btn-success btn-lg">Continue Shopping</a>
                                    <a href="customer_order.php" class="btn btn-primary btn-lg">View Orders</a>
                                </div>
                                <div class="panel-footer"></div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </body>

            </html>
            <?php
        } else {
            header("location:index.php");
            exit();
        }
    } else {
        // Payment not completed
        echo "<p style='text-align:center;color:red;'>Payment failed or incomplete.</p>";
        echo "<a href='cart.php' class='btn btn-warning'>Go back to Cart</a>";
        exit();
    }
} else {
    header("location:index.php");
    exit();
}
?>