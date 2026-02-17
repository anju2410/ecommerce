<?php
require_once("config/db.php");

if(isset($_GET['id'])) {

    $cart_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->execute([$cart_id]);
}

header("Location: cart.php");
exit;
