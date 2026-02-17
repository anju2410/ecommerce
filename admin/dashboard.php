<?php
require "config/db.php";
include "includes/header.php";

/* KPI DATA */
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status='Paid'")->fetchColumn();

/* ORDER STATUS COUNTS */
$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status='Pending'")->fetchColumn();
$processing = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status='Processing'")->fetchColumn();
$shipped = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status='Shipped'")->fetchColumn();
$delivered = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status='Delivered'")->fetchColumn();
$cancelled = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status='Cancelled'")->fetchColumn();

/* LAST 5 ORDERS */
$recentOrders = $pdo->query("
SELECT o.*, u.name as user
FROM orders o
JOIN users u ON o.user_id=u.id
ORDER BY o.id DESC
LIMIT 5
")->fetchAll();
?>

<h3>Dashboard Overview</h3>

<div class="row">

<div class="col-md-3">
<div class="small-box bg-info">
<div class="inner">
<h4><?= $totalOrders ?></h4>
<p>Total Orders</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-success">
<div class="inner">
<h4>₹<?= $totalRevenue ?: 0 ?></h4>
<p>Total Revenue</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-warning">
<div class="inner">
<h4><?= $totalUsers ?></h4>
<p>Total Users</p>
</div>
</div>
</div>

<div class="col-md-3">
<div class="small-box bg-danger">
<div class="inner">
<h4><?= $totalProducts ?></h4>
<p>Total Products</p>
</div>
</div>
</div>

</div>

<hr>

<h4>Order Status Overview</h4>

<div class="row">

<div class="col-md-2">
<div class="small-box bg-warning">
<div class="inner">
<h4><?= $pending ?></h4>
<p>Pending</p>
</div>
<div class="icon">
<i class="fas fa-clock"></i>
</div>
</div>
</div>

<div class="col-md-2">
<div class="small-box bg-info">
<div class="inner">
<h4><?= $processing ?></h4>
<p>Processing</p>
</div>
<div class="icon">
<i class="fas fa-cogs"></i>
</div>
</div>
</div>

<div class="col-md-2">
<div class="small-box bg-primary">
<div class="inner">
<h4><?= $shipped ?></h4>
<p>Shipped</p>
</div>
<div class="icon">
<i class="fas fa-truck"></i>
</div>
</div>
</div>

<div class="col-md-2">
<div class="small-box bg-success">
<div class="inner">
<h4><?= $delivered ?></h4>
<p>Delivered</p>
</div>
<div class="icon">
<i class="fas fa-check-circle"></i>
</div>
</div>
</div>

<div class="col-md-2">
<div class="small-box bg-danger">
<div class="inner">
<h4><?= $cancelled ?></h4>
<p>Cancelled</p>
</div>
<div class="icon">
<i class="fas fa-times-circle"></i>
</div>
</div>
</div>

</div>


<hr>

<h4>Recent Orders</h4>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>User</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
</tr>

<?php foreach($recentOrders as $order): ?>
<tr>
<td><?= $order['id'] ?></td>
<td><?= $order['user'] ?></td>
<td><?= $order['total_amount'] ?></td>
<td><?= $order['payment_status'] ?></td>
<td><?= $order['order_status'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php include "includes/footer.php"; ?>
