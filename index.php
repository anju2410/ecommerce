<?php
require_once("includes/header.php");

// Filtering Logic

if(isset($_GET['subcategory'])) {

    $subcategory_id = $_GET['subcategory'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE subcategory_id = ?");
    $stmt->execute([$subcategory_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif(isset($_GET['category'])) {

    $category_id = $_GET['category'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {

    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2 class="mb-4">Products</h2>

<div class="row">

<?php if(!empty($products)): ?>

    <?php foreach($products as $row): ?>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="assets/images/<?php echo $row['image']; ?>" 
                     class="card-img-top" height="200" style="object-fit:cover;">

                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                    <p class="card-text text-success fw-bold">
                        ₹ <?php echo $row['price']; ?>
                    </p>

                    <a href="product_details.php?id=<?php echo $row['id']; ?>"  class="btn btn-primary">View Details
</a>
 </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="col-12">
        <div class="alert alert-warning">
            No products found.
        </div>
    </div>

<?php endif; ?>

</div>

<?php require_once("includes/footer.php"); ?>
