<?php require_once("includes/header.php");

$category_id = $_GET['id'] ?? 0;
$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page - 1) * $limit;

/* Price Filter */
$min_price = $_GET['min'] ?? 0;
$max_price = $_GET['max'] ?? 999999;

$total_stmt = $conn->prepare("SELECT COUNT(*) FROM products 
WHERE category_id=? AND price BETWEEN ? AND ?");
$total_stmt->execute([$category_id,$min_price,$max_price]);
$total_products = $total_stmt->fetchColumn();

$total_pages = ceil($total_products / $limit);

$stmt = $conn->prepare("SELECT * FROM products 
WHERE category_id=? AND price BETWEEN ? AND ?
LIMIT $limit OFFSET $offset");
$stmt->execute([$category_id,$min_price,$max_price]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4 class="mb-4">Category Products</h4>

<!-- PRICE FILTER -->
<form method="GET" class="row mb-4">
    <input type="hidden" name="id" value="<?php echo $category_id; ?>">
    <div class="col-md-3">
        <input type="number" name="min" class="form-control" placeholder="Min Price">
    </div>
    <div class="col-md-3">
        <input type="number" name="max" class="form-control" placeholder="Max Price">
    </div>
    <div class="col-md-2">
        <button class="btn btn-dark">Filter</button>
    </div>
</form>

<div class="row">
<?php foreach($products as $product): ?>

<div class="col-md-4 mb-4">
<div class="card product-card h-100">

<img src="assets/images/<?php echo $product['image']; ?>"
     class="card-img-top"
     style="height:200px; object-fit:cover;">

<div class="card-body d-flex flex-column">
<h6><?php echo $product['name']; ?></h6>

<?php
$rating_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM product_ratings WHERE product_id=?");
$rating_stmt->execute([$product['id']]);
$avg_rating = round($rating_stmt->fetchColumn());
?>

<p>
<?php for($i=1;$i<=5;$i++): ?>
    <?php echo $i <= $avg_rating ? "⭐" : "☆"; ?>
<?php endfor; ?>
</p>

<p class="fw-bold">₹ <?php echo $product['price']; ?></p>

<form method="POST" action="add_to_cart.php">
<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
<button class="btn btn-success btn-sm mt-auto">Add to Cart</button>
</form>

<a href="product_details.php?id=<?php echo $product['id']; ?>" 
   class="btn btn-primary btn-sm mt-2">
   View
</a>

</div>
</div>
</div>

<?php endforeach; ?>
</div>

<!-- PAGINATION -->
<nav>
<ul class="pagination">
<?php for($i=1;$i<=$total_pages;$i++): ?>
<li class="page-item <?php echo ($i==$page)?'active':''; ?>">
<a class="page-link" 
href="?id=<?php echo $category_id; ?>&page=<?php echo $i; ?>">
<?php echo $i; ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>

<?php require_once("includes/footer.php"); ?>
