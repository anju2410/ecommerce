<?php
require "config/db.php";
include "includes/header.php";

/* ADD */
if(isset($_POST['add'])){
    $stmt = $pdo->prepare("INSERT INTO subcategories (category_id, name) VALUES (?,?)");
    $stmt->execute([$_POST['category_id'], $_POST['name']]);
    header("Location: subcategories.php");
    exit;
}

/* DELETE */
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM subcategories WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: subcategories.php");
    exit;
}

/* GET EDIT DATA */
$editData = null;
if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM subcategories WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

/* UPDATE */
if(isset($_POST['update'])){
    $stmt = $pdo->prepare("UPDATE subcategories SET category_id=?, name=? WHERE id=?");
    $stmt->execute([
        $_POST['category_id'],
        $_POST['name'],
        $_POST['id']
    ]);
    header("Location: subcategories.php");
    exit;
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

<!-- ADD / UPDATE FORM -->
<form method="POST" class="mb-3">

<?php if($editData): ?>
<input type="hidden" name="id" value="<?= $editData['id'] ?>">
<?php endif; ?>

<select name="category_id" class="form-control mb-2" required>
<option value="">Select Category</option>
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id'] ?>"
<?= ($editData && $editData['category_id']==$cat['id']) ? 'selected' : '' ?>>
<?= $cat['name'] ?>
</option>
<?php endforeach; ?>
</select>

<input type="text" 
       name="name" 
       class="form-control mb-2" 
       placeholder="Subcategory Name"
       value="<?= $editData ? $editData['name'] : '' ?>"
       required>

<?php if($editData): ?>
<button name="update" class="btn btn-success">Update Subcategory</button>
<a href="subcategories.php" class="btn btn-secondary">Cancel</a>
<?php else: ?>
<button name="add" class="btn btn-primary">Add Subcategory</button>
<?php endif; ?>

</form>

<!-- TABLE -->
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
<a href="?edit=<?= $sub['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?= $sub['id'] ?>" class="btn btn-danger btn-sm"
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
