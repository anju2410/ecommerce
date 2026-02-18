<?php
require "config/db.php";
include "includes/header.php";

/* ADD PRODUCT */
if(isset($_POST['add'])){

    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/".$image);

    $stmt = $pdo->prepare("
        INSERT INTO products 
        (category_id, subcategory_id, name, description, price, image) 
        VALUES (?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['category_id'],
        $_POST['subcategory_id'],
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $image
    ]);

    header("Location: products.php");
    exit;
}

/* DELETE */
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: products.php");
    exit;
}

/* FETCH PRODUCT FOR EDIT */
$editProduct = null;
if(isset($_GET['edit'])){
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

/* UPDATE PRODUCT */
if(isset($_POST['update'])){

    $image = $_FILES['image']['name'];

    if($image){
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/".$image);
    } else {
        $image = $_POST['old_image'];
    }

    $stmt = $pdo->prepare("
        UPDATE products 
        SET category_id=?, subcategory_id=?, name=?, description=?, price=?, image=? 
        WHERE id=?
    ");

    $stmt->execute([
        $_POST['category_id'],
        $_POST['subcategory_id'],
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $image,
        $_POST['product_id']
    ]);

    header("Location: products.php");
    exit;
}

/* PAGINATION + SEARCH */
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

if($search != ''){
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category, s.name as subcategory
        FROM products p
        JOIN categories c ON p.category_id=c.id
        JOIN subcategories s ON p.subcategory_id=s.id
        WHERE p.name LIKE ?
        ORDER BY p.id DESC
        LIMIT $start, $limit
    ");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category, s.name as subcategory
        FROM products p
        JOIN categories c ON p.category_id=c.id
        JOIN subcategories s ON p.subcategory_id=s.id
        ORDER BY p.id DESC
        LIMIT $start, $limit
    ");
    $stmt->execute();
}

$products = $stmt->fetchAll();
$total = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$pages = ceil($total / $limit);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

/* Load subcategories for edit mode */
$subcategories = [];
if($editProduct){
    $stmt = $pdo->prepare("SELECT * FROM subcategories WHERE category_id=?");
    $stmt->execute([$editProduct['category_id']]);
    $subcategories = $stmt->fetchAll();
}
?>

<div class="card">
<div class="card-header">
<h3 class="card-title">Manage Products</h3>
</div>

<div class="card-body">

<!-- ADD / UPDATE FORM -->
<form method="POST" enctype="multipart/form-data" class="mb-4">

<?php if($editProduct): ?>
<input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
<input type="hidden" name="old_image" value="<?= $editProduct['image'] ?>">
<?php endif; ?>

<select name="category_id" id="category" class="form-control mb-2" required>
<option value="">Select Category</option>
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id'] ?>"
<?= ($editProduct && $editProduct['category_id']==$cat['id'])?'selected':'' ?>>
<?= $cat['name'] ?>
</option>
<?php endforeach; ?>
</select>

<select name="subcategory_id" id="subcategory" class="form-control mb-2" required>
<option value="">Select Subcategory</option>
<?php if($editProduct): ?>
    <?php foreach($subcategories as $sub): ?>
        <option value="<?= $sub['id'] ?>"
        <?= ($editProduct['subcategory_id']==$sub['id'])?'selected':'' ?>>
        <?= $sub['name'] ?>
        </option>
    <?php endforeach; ?>
<?php endif; ?>
</select>

<input type="text" name="name" class="form-control mb-2"
value="<?= $editProduct['name'] ?? '' ?>" placeholder="Product Name" required>

<input type="text" name="description" class="form-control mb-2"
value="<?= $editProduct['description'] ?? '' ?>" placeholder="Description">

<input type="number" step="0.01" name="price" class="form-control mb-2"
value="<?= $editProduct['price'] ?? '' ?>" placeholder="Price" required>

<?php if($editProduct && $editProduct['image']): ?>
<div class="mb-2">
<label>Current Image:</label><br>
<img src="../assets/images/<?= $editProduct['image'] ?>" width="100">
</div>
<?php endif; ?>

<input type="file" name="image" class="form-control mb-2">

<?php if($editProduct): ?>
<button name="update" class="btn btn-success">Update</button>
<a href="products.php" class="btn btn-secondary">Cancel</a>
<?php else: ?>
<button name="add" class="btn btn-primary">Add Product</button>
<?php endif; ?>

</form>

<!-- SEARCH -->
<form method="GET" class="mb-3">
<input type="text" name="search" value="<?= $search ?>" 
placeholder="Search product..." 
class="form-control" 
style="width:250px; display:inline;">
<button class="btn btn-info">Search</button>
</form>

<!-- TABLE -->
<table class="table table-bordered table-hover">
<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Subcategory</th>
<th>Price</th>
<th>Image</th>
<th>Action</th>
</tr>

<?php foreach($products as $p): ?>
<tr>
<td><?= $p['id'] ?></td>
<td><?= $p['name'] ?></td>
<td><?= $p['category'] ?></td>
<td><?= $p['subcategory'] ?></td>
<td>₹<?= $p['price'] ?></td>
<td><img src="../assets/images/<?= $p['image'] ?>" width="50"></td>
<td>
<a href="?edit=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?= $p['id'] ?>" 
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
<a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>
</div>

<script>
$("#category").change(function(){
    var category_id = $(this).val();
    $.ajax({
        url: "get_subcategories.php",
        method: "POST",
        data: {category_id: category_id},
        success: function(data){
            $("#subcategory").html(data);
        }
    });
});
</script>

<?php include "includes/footer.php"; ?>
