<?php
session_start();
if (!isset($_SESSION["uid"])) {
    header("location:index.php");
    exit();
}
include_once("db.php");

// Get logged in user
$user_id = $_SESSION["uid"];

// Fetch pending orders (or cart items)
$sql = "
    SELECT o.order_id, o.qty, o.p_status,
           p.product_title, p.product_price
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    WHERE o.user_id = '$user_id' AND o.p_status='Pending'
";
$query = mysqli_query($con, $sql);

$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $items[] = $row;
    $total += $row['qty'] * $row['product_price'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>

<body>
    <div class="container" style="margin-top:60px;">
        <h2>Checkout</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['product_title']; ?></td>
                        <td>$<?= $item['product_price']; ?></td>
                        <td><?= $item['qty']; ?></td>
                        <td>$<?= $item['qty'] * $item['product_price']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Grand Total</strong></td>
                    <td><strong>$<?= $total; ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- PayPal Sandbox Button -->
        <form action="payment_success.php" method="post">
            <input type="hidden" name="amount" value="<?= $total; ?>">
            <button type="submit" class="btn btn-success btn-lg">Pay with PayPal (Sandbox)</button>
        </form>
    </div>
</body>

</html>