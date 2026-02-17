<?php require_once("includes/header.php");

$subcategory_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE subcategory_id = ?");
$stmt->execute([$subcategory_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4 class="mb-4">Subcategory Products</h4>

<div class="row">
<?php if(count($products)>0): ?>
<?php foreach($products as $product): ?>
<div class="col-md-3 mb-4">
<div class="card product-card h-100">
<img src="assets/images/<?php echo $product['image']; ?>" 
     class="card-img-top" style="height:200px; object-fit:cover;">

<div class="card-body d-flex flex-column">
<h6><?php echo $product['name']; ?></h6>
<p class="fw-bold">₹ <?php echo $product['price']; ?></p>

<?php
$rating_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating 
                               FROM product_ratings 
                               WHERE product_id=?");
$rating_stmt->execute([$product['id']]);
$avg_rating = round($rating_stmt->fetchColumn());
?>

<p class="mb-1">
<?php for($i=1;$i<=5;$i++): ?>
    <?php echo $i <= $avg_rating ? "⭐" : "☆"; ?>
<?php endfor; ?>
</p>

<a href="product_details.php?id=<?php echo $product['id']; ?>" 
   class="btn btn-primary btn-sm mt-auto">
   View Product
</a>
</div>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p>No products found.</p>
<?php endif; ?>
</div>

<?php require_once("includes/footer.php"); ?>
