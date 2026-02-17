<?php
require_once("config/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Must be logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])) {
    header("Location: my_orders.php");
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check order belongs to user
$stmt = $conn->prepare("
    SELECT * FROM orders 
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$order) {
    header("Location: my_orders.php");
    exit;
}

// Fetch order items
$stmt = $conn->prepare("
    SELECT order_items.*, products.name, products.image
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once("includes/header.php");
?>

<h2>Order Details - #<?php echo $order['id']; ?></h2>

<p><strong>Total:</strong> ₹ <?php echo $order['total_amount']; ?></p>
<p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
<p><strong>Date:</strong> <?php echo $order['created_at']; ?></p>

<hr>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach($items as $item): ?>
        <tr>
            <td>
                <img src="assets/images/<?php echo $item['image']; ?>" width="70">
            </td>
            <td><?php echo $item['name']; ?></td>
            <td>₹ <?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₹ <?php echo $item['price'] * $item['quantity']; ?></td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<a href="my_orders.php" class="btn btn-secondary">Back to Orders</a>

<?php require_once("includes/footer.php"); ?>
