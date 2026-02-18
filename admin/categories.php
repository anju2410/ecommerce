<?php
require "config/db.php";
include "includes/header.php";

/* ADD CATEGORY */
if(isset($_POST['add'])){
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$_POST['name']]);
    header("Location: categories.php");
    exit;
}

/* DELETE */
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: categories.php");
    exit;
}

/* FETCH FOR EDIT */
$editCategory = null;
if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

/* UPDATE CATEGORY */
if(isset($_POST['update'])){
    $stmt = $pdo->prepare("UPDATE categories SET name=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['category_id']
    ]);
    header("Location: categories.php");
    exit;
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

<!-- ADD / UPDATE FORM -->
<form method="POST" class="mb-3">

<?php if($editCategory): ?>
<input type="hidden" name="category_id" value="<?= $editCategory['id'] ?>">
<?php endif; ?>

<input type="text" 
       name="name" 
       class="form-control mb-2" 
       placeholder="Category Name" 
       value="<?= $editCategory['name'] ?? '' ?>" 
       required>

<?php if($editCategory): ?>
<button name="update" class="btn btn-success">Update</button>
<a href="categories.php" class="btn btn-secondary">Cancel</a>
<?php else: ?>
<button name="add" class="btn btn-primary">Add Category</button>
<?php endif; ?>

</form>

<!-- TABLE -->
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
<a href="?edit=<?= $cat['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?= $cat['id'] ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<!-- PAGINATION -->
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
