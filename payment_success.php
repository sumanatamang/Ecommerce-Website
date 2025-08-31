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

    // Check if payment status is Completed
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
                <title>Payment Success</title>
                <link rel="stylesheet" href="css/bootstrap.min.css" />
                <script src="js/jquery2.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="main.js"></script>
                <style>
                    table tr td {
                        padding: 10px;
                    }
                </style>
            </head>

            <body>
                <div class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="#" class="navbar-brand">Ecommerce</a>
                        </div>
                        <ul class="nav navbar-nav">
                            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                            <li><a href="profile.php"><span class="glyphicon glyphicon-modal-window"></span> Product</a></li>
                        </ul>
                    </div>
                </div>
                <p><br /><br /><br /></p>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading"></div>
                                <div class="panel-body text-center">
                                    <h1>Thank You!</h1>
                                    <hr />
                                    <p>Hello <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>, your payment has been
                                        successfully completed. <br />
                                        Transaction ID: <b><?php echo htmlspecialchars($trx_id); ?></b><br />
                                        You can continue your shopping below.</p>
                                    <a href="index.php" class="btn btn-success btn-lg">Continue Shopping</a>
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