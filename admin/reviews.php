<?php
require_once("config/db.php");
require_once("includes/auth.php");
require_once("includes/header.php");
require_once("includes/sidebar.php");

/* DELETE REVIEW */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("DELETE FROM product_ratings WHERE id=?");
    $stmt->execute([$id]);

    header("Location: reviews.php");
    exit;
}

/* PAGINATION */
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if($page < 1) $page = 1;

$limit = 10;
$offset = ($page - 1) * $limit;

/* TOTAL COUNT */
$total_stmt = $pdo->query("SELECT COUNT(*) FROM product_ratings");
$total_reviews = $total_stmt->fetchColumn();
$total_pages = ceil($total_reviews / $limit);

/* FETCH REVIEWS */
$stmt = $pdo->prepare("
    SELECT r.*, 
           p.name AS product_name, 
           u.name AS user_name
    FROM product_ratings r
    JOIN products p ON r.product_id = p.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

<h4 class="mb-4">Manage Reviews</h4>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered table-hover">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Product</th>
    <th>User</th>
    <th>Rating</th>
    <th>Review</th>
    <th>Date</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php if(count($reviews) > 0): 
$i = $offset + 1;
foreach($reviews as $rev): ?>

<tr>
<td><?php echo $i++; ?></td>

<td><?php echo htmlspecialchars($rev['product_name']); ?></td>

<td><?php echo htmlspecialchars($rev['user_name']); ?></td>

<td>
<?php for($s=1;$s<=5;$s++): ?>
    <?php echo $s <= $rev['rating'] ? "⭐" : "☆"; ?>
<?php endfor; ?>
</td>

<td><?php echo nl2br(htmlspecialchars($rev['review'])); ?></td>

<td><?php echo date("d M Y", strtotime($rev['created_at'])); ?></td>

<td>
<a href="reviews.php?delete=<?php echo $rev['id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete this review?');">
   Delete
</a>
</td>

</tr>

<?php endforeach;
else: ?>

<tr>
<td colspan="7" class="text-center">No reviews found</td>
</tr>

<?php endif; ?>

</tbody>
</table>

</div>
</div>

<!-- PAGINATION -->
<?php if($total_pages > 1): ?>
<nav class="mt-3">
<ul class="pagination">

<?php for($i=1;$i<=$total_pages;$i++): ?>
<li class="page-item <?php echo ($i==$page)?'active':''; ?>">
<a class="page-link" href="?page=<?php echo $i; ?>">
<?php echo $i; ?>
</a>
</li>
<?php endfor; ?>

</ul>
</nav>
<?php endif; ?>

</div>

<?php require_once("includes/footer.php"); ?>
