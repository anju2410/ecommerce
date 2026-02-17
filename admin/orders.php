<?php
require_once("../config/db.php");
session_start();
include("includes/header.php");

/* UPDATE ORDER STATUS */
if(isset($_POST['update_status'])){

    $order_id = $_POST['order_id'];
    $status   = $_POST['order_status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE id=?");
    $stmt->execute([$status, $order_id]);
}

/* PAGINATION */
$limit = 10;
$page  = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pages = ceil($total / $limit);

/* FETCH ORDERS */
$stmt = $conn->prepare("
    SELECT o.*, u.name as username
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
    LIMIT $start, $limit
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
<div class="card-header">
    <h3 class="card-title">Manage Orders</h3>
</div>

<div class="card-body">

<table class="table table-bordered table-hover text-center">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Total Amount</th>
    <th>Status</th>
    <th>Date</th>
    <th>Update</th>
</tr>
</thead>

<tbody>
<?php foreach($orders as $order): ?>

<?php
$status = $order['order_status'] ?? 'Pending';

$badge = 'secondary';
if($status == 'Pending')      $badge = 'warning';
if($status == 'Processing')   $badge = 'primary';
if($status == 'Shipped')      $badge = 'info';
if($status == 'Delivered')    $badge = 'success';
if($status == 'Cancelled')    $badge = 'danger';
?>

<tr>
    <td><?= $order['id'] ?></td>
    <td><?= htmlspecialchars($order['username']) ?></td>
    <td>₹ <?= $order['total_amount'] ?></td>

    <td>
        <span class="badge bg-<?= $badge ?>">
            <?= $status ?>
        </span>
    </td>

    <td><?= $order['created_at'] ?></td>

    <td>
        <form method="POST" class="d-flex">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

            <select name="order_status" class="form-select form-select-sm me-2">
                <option <?= ($status=='Pending')?'selected':'' ?>>Pending</option>
                <option <?= ($status=='Processing')?'selected':'' ?>>Processing</option>
                <option <?= ($status=='Shipped')?'selected':'' ?>>Shipped</option>
                <option <?= ($status=='Delivered')?'selected':'' ?>>Delivered</option>
                <option <?= ($status=='Cancelled')?'selected':'' ?>>Cancelled</option>
            </select>

            <button type="submit" name="update_status" 
                    class="btn btn-sm btn-success">
                Update
            </button>
        </form>
    </td>
</tr>

<?php endforeach; ?>
</tbody>
</table>

<!-- PAGINATION -->
<nav>
<ul class="pagination justify-content-center">
<?php for($i=1; $i<=$pages; $i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?page=<?= $i ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>
</div>

<?php include("includes/footer.php"); ?>
