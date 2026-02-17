<?php
include '../config/db.php';
$id = $_POST['order_id'];
$status = $_POST['status'];
$conn->query("UPDATE orders SET order_status='$status' WHERE id=$id");
header("Location: orders.php");
?>
