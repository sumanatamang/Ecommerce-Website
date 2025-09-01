<?php
session_start();

class Customers
{
	private $con;

	function __construct()
	{
		include_once("Database.php");
		$db = new Database();
		$this->con = $db->connect();
	}

	// Fetch all customers
	public function getCustomers()
	{
		$query = $this->con->query("
            SELECT user_id, first_name, last_name, email, mobile, address1, address2 
            FROM user_info
        ");

		$ar = [];
		if ($query && $query->num_rows > 0) {
			while ($row = $query->fetch_assoc()) {
				$ar[] = $row;
			}
			return ['status' => 202, 'message' => $ar];
		}
		return ['status' => 303, 'message' => 'No customer data found'];
	}

	// Fetch all customer orders with shipment info
	public function getCustomersOrder()
	{
		$query = $this->con->query("
            SELECT 
                o.order_id, o.product_id, o.qty, o.trx_id, o.p_status,
                p.product_title, p.product_image,
                COALESCE(s.shipment_status, 'Pending') AS shipment_status,
                s.tracking_number
            FROM orders o
            JOIN products p ON o.product_id = p.product_id
            LEFT JOIN shipments s ON o.order_id = s.order_id
            ORDER BY o.order_id DESC
        ");

		$ar = [];
		if ($query && $query->num_rows > 0) {
			while ($row = $query->fetch_assoc()) {
				$ar[] = $row;
			}
			return ['status' => 202, 'message' => $ar];
		}
		return ['status' => 303, 'message' => 'No orders yet'];
	}
} // End of class

// ------------------- AJAX HANDLERS -------------------

// Get all customers
if (isset($_POST["GET_CUSTOMERS"])) {
	if (isset($_SESSION['admin_id'])) {
		$c = new Customers();
		echo json_encode($c->getCustomers());
		exit();
	}
}

// Get all customer orders
if (isset($_POST["GET_CUSTOMER_ORDERS"])) {
	if (isset($_SESSION['admin_id'])) {
		$c = new Customers();
		echo json_encode($c->getCustomersOrder());
		exit();
	}
}
?>