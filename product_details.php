<?php require_once("includes/header.php");

$product_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product){
    echo "<h4>Product not found</h4>";
    require_once("includes/footer.php");
    exit;
}

/* HANDLE RATING + REVIEW SUBMISSION */
if(isset($_POST['rating']) && isset($_SESSION['user_id'])){

    $rating = $_POST['rating'];
    $review_text = trim($_POST['review'] ?? '');

    $check = $conn->prepare("SELECT * FROM product_ratings 
                             WHERE product_id=? AND user_id=?");
    $check->execute([$product_id,$_SESSION['user_id']]);

    if($check->rowCount() == 0){
        $conn->prepare("INSERT INTO product_ratings(product_id,user_id,rating,review)
                        VALUES(?,?,?,?)")
              ->execute([$product_id,$_SESSION['user_id'],$rating,$review_text]);
    } else {
        $conn->prepare("UPDATE product_ratings 
                        SET rating=?, review=? 
                        WHERE product_id=? AND user_id=?")
              ->execute([$rating,$review_text,$product_id,$_SESSION['user_id']]);
    }
}

/* FETCH AVERAGE RATING */
$rating_stmt = $conn->prepare("
    SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
    FROM product_ratings 
    WHERE product_id=?");
$rating_stmt->execute([$product_id]);
$rating_data = $rating_stmt->fetch(PDO::FETCH_ASSOC);

$avg_rating = round($rating_data['avg_rating']);
$total_reviews = $rating_data['total_reviews'];

/* FETCH USER RATING */
$user_rating = 0;
$user_review = '';

if(isset($_SESSION['user_id'])){
    $stmt = $conn->prepare("SELECT rating, review 
                            FROM product_ratings 
                            WHERE product_id=? AND user_id=?");
    $stmt->execute([$product_id,$_SESSION['user_id']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($data){
        $user_rating = $data['rating'];
        $user_review = $data['review'];
    }
}
?>

<div class="row">
<div class="col-md-6">
<img src="assets/images/<?php echo $product['image']; ?>"
     class="img-fluid rounded shadow">
</div>

<div class="col-md-6">
<h3><?php echo $product['name']; ?></h3>
<p><?php echo $product['description']; ?></p>

<!-- Average Rating -->
<p>
<?php for($i=1;$i<=5;$i++): ?>
    <?php echo $i <= $avg_rating ? "⭐" : "☆"; ?>
<?php endfor; ?>
<span class="ms-2 text-muted">(<?php echo $total_reviews; ?> reviews)</span>
</p>

<h4 class="text-success">₹ <?php echo $product['price']; ?></h4>

<form method="POST" action="add_to_cart.php">
<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
<button class="btn btn-success mt-2">Add to Cart</button>
</form>

<hr>

<h5>Write a Review</h5>

<?php if(isset($_SESSION['user_id'])): ?>

<style>
.star-rating {
    direction: rtl;
    display: inline-flex;
}
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 28px;
    color: #ccc;
    cursor: pointer;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: gold;
}
</style>

<form method="POST">

<div class="star-rating mb-2">
<?php for($i=5;$i>=1;$i--): ?>
    <input type="radio" name="rating"
           value="<?php echo $i; ?>"
           id="star<?php echo $i; ?>"
           <?php if($user_rating==$i) echo "checked"; ?>>
    <label for="star<?php echo $i; ?>">★</label>
<?php endfor; ?>
</div>

<textarea name="review"
          class="form-control"
          placeholder="Write your review..."><?php echo htmlspecialchars($user_review); ?></textarea>

<button class="btn btn-dark btn-sm mt-2">Submit Review</button>

</form>

<?php else: ?>
<p>Please login to write a review.</p>
<?php endif; ?>

</div>
</div>

<hr>

<h4>Customer Reviews</h4>

<?php
$reviews = $conn->prepare("
    SELECT r.*, u.name 
    FROM product_ratings r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id=?
    ORDER BY r.created_at DESC
");
$reviews->execute([$product_id]);

if($reviews->rowCount() > 0):
foreach($reviews as $rev):
?>

<div class="card mb-3">
<div class="card-body">
<h6><?php echo htmlspecialchars($rev['name']); ?></h6>

<p>
<?php for($i=1;$i<=5;$i++): ?>
    <?php echo $i <= $rev['rating'] ? "⭐" : "☆"; ?>
<?php endfor; ?>
</p>

<p><?php echo nl2br(htmlspecialchars($rev['review'])); ?></p>

<small class="text-muted">
<?php echo date("d M Y", strtotime($rev['created_at'])); ?>
</small>

</div>
</div>

<?php endforeach;
else: ?>

<p>No reviews yet.</p>

<?php endif; ?>

<hr>

<h4>Related Products</h4>

<div class="row">
<?php
$related = $conn->prepare("
    SELECT * FROM products 
    WHERE category_id=? AND id!=? 
    LIMIT 4");
$related->execute([$product['category_id'],$product_id]);

foreach($related as $rel):
?>

<div class="col-md-3 mb-4">
<div class="card product-card h-100 shadow-sm">

<img src="assets/images/<?php echo $rel['image']; ?>"
     class="card-img-top"
     style="height:200px; object-fit:cover;">

<div class="card-body">
<h6><?php echo $rel['name']; ?></h6>

<a href="product_details.php?id=<?php echo $rel['id']; ?>"
   class="btn btn-sm btn-primary w-100">
   View
</a>

</div>
</div>
</div>

<?php endforeach; ?>
</div>

<?php require_once("includes/footer.php"); ?>
