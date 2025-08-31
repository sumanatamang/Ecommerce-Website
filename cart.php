<?php
require "config/constants.php";
session_start();

if (!isset($_SESSION["uid"])) {
    header("location:index.php");
    exit();
}

$con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);
if ($con->connect_error)
    die("Database connection failed: " . $con->connect_error);

$uid = $_SESSION["uid"];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Ecommerce</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
    <style>
        .cart-container {
            margin-top: 70px;
        }

        .cart-card {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }

        .cart-card-details {
            flex-grow: 1;
        }

        .cart-card-details h5 {
            margin: 0 0 5px 0;
        }

        .cart-card-details p {
            margin: 2px 0;
        }

        .cart-actions {
            text-align: right;
        }

        .cart-actions input.qty {
            width: 60px;
            display: inline-block;
        }

        .grand-total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
        }

        .empty-cart {
            text-align: center;
            padding: 50px 0;
            font-size: 24px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container cart-container">
        <h2 class="text-center mb-4">Your Shopping Cart</h2>
        <div id="cart_msg"></div>

        <?php
        $stmt = $con->prepare("SELECT c.id, c.qty, p.product_id, p.product_title, p.product_price, p.product_image 
                               FROM cart c 
                               JOIN products p ON c.p_id = p.product_id 
                               WHERE c.user_id=?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        $grand_total = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $total_price = $row['product_price'] * $row['qty'];
                $grand_total += $total_price;
                ?>
                <div class="cart-card" data-price="<?= $row['product_price']; ?>" data-pid="<?= $row['product_id']; ?>">
                    <img src="product_images/<?= $row['product_image']; ?>" alt="<?= $row['product_title']; ?>">
                    <div class="cart-card-details">
                        <h5><?= $row['product_title']; ?></h5>
                        <p>Price: <?= CURRENCY . number_format($row['product_price'], 2); ?></p>
                        <p>Total: <span class="item-total"><?= CURRENCY . number_format($total_price, 2); ?></span></p>
                        <p>Quantity:
                            <input type="number" class="qty" value="<?= $row['qty']; ?>" min="1">
                            <button class="btn btn-primary btn-sm btn-update">Update</button>
                        </p>
                    </div>
                    <div class="cart-actions">
                        <button class="btn btn-danger btn-sm btn-remove">Remove</button>
                    </div>
                </div>
                <?php
            }
            echo '<div class="grand-total">Grand Total: <span id="grand_total">' . CURRENCY . number_format($grand_total, 2) . '</span>
                  <a href="checkout.php" class="btn btn-success btn-lg ml-3">Proceed to Checkout</a></div>';
        } else {
            echo '<div class="empty-cart">Your cart is empty! Start shopping now.</div>';
        }

        $stmt->close();
        ?>
    </div>

    <script>
        $(document).ready(function () {

            function recalcGrandTotal() {
                let total = 0;
                $('.cart-card').each(function () {
                    total += parseFloat($(this).find('.item-total').data('value') || 0);
                });
                $('#grand_total').text('<?= CURRENCY ?>' + total.toFixed(2));
            }

            // Remove item
            $('.btn-remove').click(function () {
                let card = $(this).closest('.cart-card');
                let pid = card.data('pid');
                $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: { removeFromCart: 1, proId: pid },
                    success: function () {
                        card.remove();
                        recalcGrandTotal();
                        if ($('.cart-card').length == 0)
                            $('#cart_msg').html('<div class="empty-cart">Your cart is empty! Start shopping now.</div>');
                    }
                });
            });

            // Update quantity
            $('.btn-update').click(function () {
                let card = $(this).closest('.cart-card');
                let pid = card.data('pid');
                let qty = parseInt(card.find('.qty').val());
                if (qty < 1) qty = 1;

                $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: { updateCart: 1, proId: pid, qty: qty },
                    success: function () {
                        let price = parseFloat(card.data('price'));
                        let total = qty * price;
                        card.find('.item-total').text('<?= CURRENCY ?>' + total.toFixed(2)).data('value', total);
                        recalcGrandTotal();
                    }
                });
            });

        });
    </script>
</body>

</html>