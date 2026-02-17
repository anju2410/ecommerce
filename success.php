<?php
require_once("config/db.php");
require_once("config/stripe.php");

session_start();

if (!isset($_GET['session_id'])) {
    die("Invalid access.");
}

$session_id = $_GET['session_id'];

// Retrieve Stripe session
$session = \Stripe\Checkout\Session::retrieve($session_id);

// Get order ID from metadata using client_reference_id
$order_id = $session->client_reference_id ?? null;

if ($session->payment_status === 'paid' && $order_id) {

    // Update order
    $stmt = $conn->prepare("
        UPDATE orders 
        SET payment_status = 'Paid',
            order_status = 'Processing'
        WHERE id = ?
    ");
    $stmt->execute([$order_id]);

    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    // Redirect to My Orders page
    header("Location: my_orders.php");
    exit;

} else {
    echo "Payment verification failed.";
}
?>
