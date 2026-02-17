<?php
require "config/db.php";
include "includes/header.php";

/* ADD CATEGORY */
if(isset($_POST['add'])){
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$_POST['name']]);
}

/* DELETE */
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$_GET['delete']]);
}

/* PAGINATION */
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$pages = ceil($total / $limit);

$stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $start, $limit");
$stmt->execute();
$categories = $stmt->fetchAll();
?>

<div class="card">
<div class="card-header">
<h3 class="card-title">Manage Categories</h3>
</div>

<div class="card-body">

<form method="POST" class="mb-3">
<input type="text" name="name" class="form-control mb-2" placeholder="Category Name" required>
<button name="add" class="btn btn-primary">Add Category</button>
</form>

<table class="table table-bordered table-striped">
<tr>
<th>ID</th>
<th>Name</th>
<th>Action</th>
</tr>

<?php foreach($categories as $cat): ?>
<tr>
<td><?= $cat['id'] ?></td>
<td><?= $cat['name'] ?></td>
<td>
<a href="?delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<nav>
<ul class="pagination">
<?php for($i=1; $i<=$pages; $i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>
</div>

<?php include "includes/footer.php"; ?>
