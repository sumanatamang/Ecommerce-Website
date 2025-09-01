<?php
session_start();
include "../admin/classes/Database.php"; // make sure the path is correct
$db = new Database();
$con = $db->connect();

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $shipment_status = $_POST['shipment_status'];
    $tracking_number = $_POST['tracking_number'];

    // Check if shipment exists
    $check = $con->prepare("SELECT shipment_id FROM shipments WHERE order_id = ?");
    $check->bind_param("i", $order_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update existing shipment
        $stmt = $con->prepare("UPDATE shipments SET shipment_status=?, tracking_number=? WHERE order_id=?");
        $stmt->bind_param("ssi", $shipment_status, $tracking_number, $order_id);
    } else {
        // Insert new shipment
        $stmt = $con->prepare("INSERT INTO shipments (order_id, shipment_status, tracking_number) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $order_id, $shipment_status, $tracking_number);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 202, 'message' => 'Shipment updated successfully']);
    } else {
        echo json_encode(['status' => 303, 'message' => 'Error: ' . $con->error]);
    }
}
?>