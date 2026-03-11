
<?php
require_once("config/db.php");
require_once("config/stripe.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("
    SELECT cart.product_id, cart.quantity, products.price, products.name
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($cart_items)) {
    header("Location: cart.php");
    exit;
}

// Calculate total
$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

if(isset($_POST['place_order'])) {

    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $payment_method = $_POST['payment_method'];

    try {

        $conn->beginTransaction();

        // Insert Order with Address Details
        $stmt = $conn->prepare("
            INSERT INTO orders 
            (user_id, full_name, phone, address, city, pincode, total_amount, payment_method, payment_status, order_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Pending')
        ");

        $stmt->execute([
            $user_id,
            $full_name,
            $phone,
            $address,
            $city,
            $pincode,
            $total,
            $payment_method
        ]);

        $order_id = $conn->lastInsertId();

        // Insert Order Items
        foreach($cart_items as $item) {

            $stmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            ]);
        }

        $conn->commit();

        // COD Order
        if($payment_method == "COD") {

            $stmt = $conn->prepare("
                UPDATE orders 
                SET order_status = 'Processing'
                WHERE id = ?
            ");
            $stmt->execute([$order_id]);

            $clearCart = $conn->prepare("DELETE FROM cart WHERE user_id=?");
            $clearCart->execute([$user_id]);

            unset($_SESSION['cart']);

            header("Location: order_success.php");
            exit;
        }

        // Stripe Checkout
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'client_reference_id' => $order_id,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => 'Order #' . $order_id,
                    ],
                    'unit_amount' => $total * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost/ecommerce/success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost/ecommerce/cancel.php?order_id=' . $order_id,
        ]);

        header("Location: " . $session->url);
        exit;

    } catch(Exception $e) {

        $conn->rollBack();
        echo "Something went wrong: " . $e->getMessage();
    }
}

require_once("includes/header.php");
?>

<h2>Checkout</h2>

<h4>Total Amount: ₹ <?php echo $total; ?></h4>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<h2 class="text-center mb-4">Checkout</h2>

<h4 class="text-center mb-4">Total Amount: ₹ <?php echo $total; ?></h4>

<form method="POST">

<div class="mb-3">
<label>Full Name</label>
<input type="text" name="full_name" class="form-control" required>
</div>

<div class="mb-3">
<label>Phone Number</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="mb-3">
<label>Delivery Address</label>
<textarea name="address" class="form-control" required></textarea>
</div>

<div class="mb-3">
<label>City</label>
<input type="text" name="city" class="form-control" required>
</div>

<div class="mb-3">
<label>Pincode</label>
<input type="text" name="pincode" class="form-control" required>
</div>

<div class="mb-3">
<label>Select Payment Method</label>

<select name="payment_method" class="form-control" required>
<option value="">-- Select Payment Method --</option>
<option value="COD">Cash on Delivery</option>
<option value="Card">Credit/Debit Card (Stripe)</option>
</select>

</div>

<button type="submit" name="place_order" class="btn btn-success w-100">
Place Order
</button>

</form>

</div>

</div>

</div>

<?php require_once("includes/footer.php"); ?>

