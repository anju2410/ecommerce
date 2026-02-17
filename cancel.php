<?php
require_once("config/db.php");
session_start();

if (!isset($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = $_GET['order_id'];

// Update order as Failed
$stmt = $conn->prepare("
    UPDATE orders 
    SET payment_status = 'Pending',
        order_status = 'Cancelled'
    WHERE id = ?
");
$stmt->execute([$order_id]);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            padding-top: 100px;
        }
        .box {
            display: inline-block;
            padding: 40px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .btn {
            padding: 10px 20px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>❌ Payment Failed or Cancelled</h2>
    <p>Your order has been marked as cancelled.</p>

    <br>

    <a href="checkout.php?retry=<?= $order_id ?>" class="btn">
        Retry Payment
    </a>
</div>

</body>
</html>
