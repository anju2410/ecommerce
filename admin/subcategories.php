<?php
require "config/db.php";
include "includes/header.php";

/* ADD */
if(isset($_POST['add'])){
    $stmt = $pdo->prepare("INSERT INTO subcategories (category_id, name) VALUES (?,?)");
    $stmt->execute([$_POST['category_id'], $_POST['name']]);
}

/* DELETE */
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM subcategories WHERE id=?");
    $stmt->execute([$_GET['delete']]);
}

/* PAGINATION */
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total = $pdo->query("SELECT COUNT(*) FROM subcategories")->fetchColumn();
$pages = ceil($total / $limit);

$stmt = $pdo->prepare("
SELECT s.*, c.name as category 
FROM subcategories s
JOIN categories c ON s.category_id=c.id
ORDER BY s.id DESC
LIMIT $start, $limit
");
$stmt->execute();
$subcategories = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="card">
<div class="card-header">
<h3 class="card-title">Manage Subcategories</h3>
</div>

<div class="card-body">

<form method="POST" class="mb-3">
<select name="category_id" class="form-control mb-2" required>
<option value="">Select Category</option>
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
<?php endforeach; ?>
</select>

<input type="text" name="name" class="form-control mb-2" placeholder="Subcategory Name" required>
<button name="add" class="btn btn-primary">Add Subcategory</button>
</form>

<table class="table table-bordered table-striped">
<tr>
<th>ID</th>
<th>Category</th>
<th>Name</th>
<th>Action</th>
</tr>

<?php foreach($subcategories as $sub): ?>
<tr>
<td><?= $sub['id'] ?></td>
<td><?= $sub['category'] ?></td>
<td><?= $sub['name'] ?></td>
<td>
<a href="?delete=<?= $sub['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
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
