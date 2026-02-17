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

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$stmt = $conn->prepare("
    SELECT * FROM orders 
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once("includes/header.php");
?>

<h2>My Orders</h2>

<?php if(!empty($orders)): ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach($orders as $order): ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td>₹ <?php echo $order['total_amount']; ?></td>
                <td><?php echo $order['payment_method']; ?></td>
                <td><?php echo $order['created_at']; ?></td>
                <td>
                    <a href="order_details.php?id=<?php echo $order['id']; ?>" 
                       class="btn btn-primary btn-sm">
                       View Details
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

<?php else: ?>

    <div class="alert alert-warning">
        You have not placed any orders yet.
    </div>

<?php endif; ?>

<?php require_once("includes/footer.php"); ?>
