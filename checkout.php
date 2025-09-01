<?php
session_start();
if (!isset($_SESSION["uid"]))
    header("location:index.php");

include_once("db.php");
$user_id = $_SESSION["uid"];

// Fetch cart items
$stmt = $con->prepare("SELECT c.id AS cart_id, c.p_id, c.qty, p.product_title, p.product_price, p.product_image 
                       FROM cart c 
                       JOIN products p ON c.p_id = p.product_id 
                       WHERE c.user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['qty'] * $row['product_price'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f8f9fa;
            padding-top: 60px;
        }

        .card {
            margin-bottom: 20px;
        }

        .card-img-top {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .product-price {
            color: #28a745;
            font-weight: bold;
        }

        .qty-input {
            width: 60px;
        }

        .total-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .total-box h4 {
            margin-bottom: 20px;
        }

        .btn-pay {
            width: 100%;
        }
    </style>
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">Checkout</h2>

        <?php if (count($items) > 0): ?>
            <div class="row">
                <div class="col-md-8">
                    <?php foreach ($items as $item): ?>
                        <div class="card checkout-item" data-cartid="<?= $item['cart_id']; ?>"
                            data-price="<?= $item['product_price']; ?>">
                            <div class="row no-gutters align-items-center">
                                <div class="col-md-3">
                                    <img src="product_images/<?= $item['product_image']; ?>" class="card-img-top">
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <p class="product-title"><?= htmlspecialchars($item['product_title']); ?></p>
                                        <p class="product-price"><?= CURRENCY . number_format($item['product_price'], 2); ?></p>
                                        <div class="input-group">
                                            <input type="number" class="form-control qty-input" min="1"
                                                value="<?= $item['qty']; ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-update-qty">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <p class="item-total product-price">
                                        <?= CURRENCY . number_format($item['product_price'] * $item['qty'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4">
                    <div class="total-box">
                        <h4>Order Summary</h4>
                        <div id="summary-items">
                            <?php foreach ($items as $item): ?>
                                <div class="d-flex justify-content-between">
                                    <span><?= htmlspecialchars($item['product_title']); ?>  <span
                                            class="summary-qty"><?= $item['qty']; ?></span></span> x
                                    <span
                                        class="summary-total"><?= CURRENCY . number_format($item['product_price'] * $item['qty'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Grand Total</strong>
                            <strong id="grand-total"><?= CURRENCY . number_format($total, 2); ?></strong>
                        </div>
                        <form action="payment_success.php" method="get" class="mt-3">
                            <input type="hidden" name="amt" id="payment-amt" value="<?= $total ?>">
                            <input type="hidden" name="st" value="Completed">
                            <input type="hidden" name="tx" value="<?= time(); ?>">
                            <input type="hidden" name="cc" value="Rs">
                            <input type="hidden" name="cm" value="<?= $user_id; ?>">
                            <button type="submit" class="btn btn-success btn-lg btn-pay">Pay with PayPal (Sandbox)</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Your cart is empty! Start shopping now.</div>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function () {
            function updateSummary() {
                let grandTotal = 0;
                $('#summary-items').empty();
                $('.checkout-item').each(function () {
                    let title = $(this).find('.product-title').text();
                    let qty = parseInt($(this).find('.qty-input').val());
                    let price = parseFloat($(this).data('price'));
                    let total = qty * price;
                    grandTotal += total;
                    $('#summary-items').append('<div class="d-flex justify-content-between"><span>' + title + ' x <span class="summary-qty">' + qty + '</span></span><span class="summary-total"><?= CURRENCY ?>' + total.toFixed(2) + '</span></div>');
                    $(this).find('.item-total').text('<?= CURRENCY ?>' + total.toFixed(2));
                });
                $('#grand-total').text('<?= CURRENCY ?>' + grandTotal.toFixed(2));
                $('#payment-amt').val(grandTotal.toFixed(2));
            }

            $('.btn-update-qty').click(function () {
                let card = $(this).closest('.checkout-item');
                let cartId = card.data('cartid');
                let qty = card.find('.qty-input').val();
                $.ajax({
                    url: 'action.php',
                    method: 'POST',
                    data: { updateCart: 1, cartId: cartId, qty: qty },
                    success: function (resp) {
                        updateSummary();
                    }
                });
            });
        });
    </script>
</body>

</html>