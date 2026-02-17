<?php
require "config/db.php";
include "includes/header.php";

$order_id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT o.*, u.name as username, u.email
    FROM orders o
    JOIN users u ON o.user_id=u.id
    WHERE o.id=?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id=p.id
    WHERE oi.order_id=?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<div class="card">
<div class="card-header">
<h3 class="card-title">Order Details #<?= $order_id ?></h3>
</div>

<div class="card-body">

<div class="row mb-4">
<div class="col-md-6">
<h5><strong>Customer Info</strong></h5>
<p>
Name: <?= $order['username'] ?><br>
Email: <?= $order['email'] ?><br>
Date: <?= $order['created_at'] ?><br>
Status: <span class="badge badge-info"><?= $order['status'] ?></span>
</p>
</div>

<div class="col-md-6">
<h5><strong>Payment Info</strong></h5>
<p>
Payment Method: <?= $order['payment_method'] ?><br>
Total Amount: ₹<?= $order['total_amount'] ?>
</p>
</div>
</div>

<table class="table table-bordered">
<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
</tr>

<?php foreach($items as $item): ?>
<tr>
<td><?= $item['name'] ?></td>
<td>₹<?= $item['price'] ?></td>
<td><?= $item['quantity'] ?></td>
<td>₹<?= $item['price'] * $item['quantity'] ?></td>
</tr>
<?php endforeach; ?>

<tr>
<td colspan="3" align="right"><strong>Grand Total</strong></td>
<td><strong>₹<?= $order['total_amount'] ?></strong></td>
</tr>
</table>

<a href="orders.php" class="btn btn-secondary">Back</a>

</div>
</div>

<?php include "includes/footer.php"; ?>
