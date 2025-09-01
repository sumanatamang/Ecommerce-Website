<?php
session_start();
if (!isset($_SESSION["uid"])) {
	header("location:index.php");
	exit();
}

// Just read the name from the session
$customer_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Account';
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Achaar Bazar - My Orders</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script src="js/jquery2.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="main.js"></script>
	<style>
		.order-card {
			border: 1px solid #ddd;
			border-radius: 5px;
			padding: 15px;
			margin-bottom: 20px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			background: #fff;
		}

		.order-card img {
			max-height: 200px;
			object-fit: cover;
			margin: 0 auto;
		}

		.order-card table tr td {
			padding: 6px 10px;
		}
	</style>
</head>

<body>
	<!-- Navbar -->
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="index.php" class="navbar-brand">Achaar Bazar</a>
			</div>
			<ul class="nav navbar-nav">
				<li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
				<li><a href="index.php#products"><span class="glyphicon glyphicon-modal-window"></span> Products</a>
				</li>
				<li class="active"><a href="customer_order.php"><span class="glyphicon glyphicon-list-alt"></span> My
						Orders</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart <span class="badge"
							id="cart_count">0</span></a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($customer_name); ?>
						<b class="caret"></b>
					</a>
					<ul class="dropdown-menu">
						<li><a href="profile.php">Profile</a></li>
						<li><a href="customer_order.php">Orders</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>

	<div style="margin-top:70px;"></div>

	<!-- Customer Orders -->
	<div class="container">
		<h2 class="text-center">My Orders</h2>
		<hr>

		<?php
		include_once("db.php");
		$user_id = $_SESSION["uid"];

		$orders_list = "
			SELECT o.order_id, o.product_id, o.qty, o.trx_id, o.p_status,
				   p.product_title, p.product_price, p.product_image,
				   COALESCE(s.shipment_status, 'Pending') AS shipment_status,
				   s.tracking_number
			FROM orders o
			JOIN products p ON o.product_id = p.product_id
			LEFT JOIN shipments s ON o.order_id = s.order_id
			WHERE o.user_id = '$user_id'
			ORDER BY o.order_id DESC
		";
		$query = mysqli_query($con, $orders_list);

		if (mysqli_num_rows($query) > 0) {
			while ($row = mysqli_fetch_assoc($query)) {
				?>
				<div class="order-card row">
					<div class="col-sm-4 text-center">
						<img src="product_images/<?php echo $row['product_image']; ?>" class="img-responsive img-thumbnail">
					</div>
					<div class="col-sm-8">
						<table class="table table-bordered">
							<tr>
								<td><b>Product Name</b></td>
								<td><?php echo $row["product_title"]; ?></td>
							</tr>
							<tr>
								<td><b>Price</b></td>
								<td><?php echo CURRENCY . " " . $row["product_price"]; ?></td>
							</tr>
							<tr>
								<td><b>Quantity</b></td>
								<td><?php echo $row["qty"]; ?></td>
							</tr>
							<tr>
								<td><b>Transaction ID</b></td>
								<td><?php echo $row["trx_id"]; ?></td>
							</tr>
							<tr>
								<td><b>Payment Status</b></td>
								<td><span
										class="label label-<?php echo ($row['p_status'] == 'Completed') ? 'success' : 'warning'; ?>">
										<?php echo $row["p_status"]; ?>
									</span></td>
							</tr>
							<tr>
								<td><b>Shipment Status</b></td>
								<td><span
										class="label label-<?php echo ($row['shipment_status'] == 'Delivered') ? 'success' : 'info'; ?>">
										<?php echo ucfirst($row['shipment_status']); ?>
									</span></td>
							</tr>
							<tr>
								<td><b>Tracking Number</b></td>
								<td><?php echo $row['tracking_number'] ?: 'N/A'; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<?php
			}
		} else {
			echo "<div class='alert alert-info text-center'>No orders found.</div>";
		}
		?>
	</div>
</body>

</html>