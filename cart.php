<?php
require_once("config/db.php");
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Update quantity
if(isset($_POST['update_quantity'])) {

    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$quantity, $cart_id, $user_id]);
}

// Fetch user cart
$stmt = $conn->prepare("
    SELECT cart.id AS cart_id, cart.quantity, 
           products.name, products.price, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;

require_once("includes/header.php");
?>

<h2>My Cart</h2>

<?php if(!empty($cart_items)): ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach($cart_items as $item): ?>
        <?php 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <tr>
            <td><img src="assets/images/<?php echo $item['image']; ?>" width="70"></td>
            <td><?php echo $item['name']; ?></td>
            <td>₹ <?php echo $item['price']; ?></td>

            <td>
                <form method="POST" class="d-flex">
                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control me-2" style="width:80px;">
                    <button type="submit" name="update_quantity" class="btn btn-primary btn-sm">
                        Update
                    </button>
                </form>
            </td>

            <td>₹ <?php echo $subtotal; ?></td>

            <td>
                <a href="remove_from_cart.php?id=<?php echo $item['cart_id']; ?>" class="btn btn-danger btn-sm">
                    Remove
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<h4 class="text-end">Total: ₹ <?php echo $total; ?></h4>

<a href="checkout.php" class="btn btn-success float-end">Checkout</a>

<?php else: ?>

<div class="alert alert-warning">Your cart is empty.</div>

<?php endif; ?>

<?php require_once("includes/footer.php"); ?>
