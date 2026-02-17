<?php
require "config/db.php";
include "includes/header.php";

/* BLOCK / UNBLOCK */
if(isset($_GET['block'])){
    $stmt = $pdo->prepare("UPDATE users SET status='Blocked' WHERE id=?");
    $stmt->execute([$_GET['block']]);
}

if(isset($_GET['unblock'])){
    $stmt = $pdo->prepare("UPDATE users SET status='Active' WHERE id=?");
    $stmt->execute([$_GET['unblock']]);
}

/* PAGINATION */
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pages = ceil($total / $limit);

$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $start, $limit");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="card">
<div class="card-header">
<h3 class="card-title">Manage Users</h3>
</div>

<div class="card-body">

<table class="table table-bordered table-hover">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th>Created</th>
<th>Action</th>
</tr>

<?php foreach($users as $user): ?>
<tr>
<td><?= $user['id'] ?></td>
<td><?= $user['name'] ?></td>
<td><?= $user['email'] ?></td>

<td>
<?php if($user['status']=='Active'): ?>
<span class="badge badge-success">Active</span>
<?php else: ?>
<span class="badge badge-danger">Blocked</span>
<?php endif; ?>
</td>

<td><?= $user['created_at'] ?></td>

<td>
<?php if($user['status']=='Active'): ?>
<a href="?block=<?= $user['id'] ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('Block this user?')">
   Block
</a>
<?php else: ?>
<a href="?unblock=<?= $user['id'] ?>" 
   class="btn btn-success btn-sm"
   onclick="return confirm('Unblock this user?')">
   Unblock
</a>
<?php endif; ?>
</td>

</tr>
<?php endforeach; ?>
</table>

<nav>
<ul class="pagination">
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

<?php include "includes/footer.php"; ?>
