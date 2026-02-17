<?php
require_once("includes/header.php");
?>
<?php
/*
// SEND CONFIRMATION EMAIL (NON-BLOCKING)
try {

    require 'includes/phpmailer/src/Exception.php';
    require 'includes/phpmailer/src/PHPMailer.php';
    require 'includes/phpmailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'yourgmail@gmail.com';   // CHANGE
    $mail->Password   = 'your_app_password';     // CHANGE
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('yourgmail@gmail.com', 'ShopHub');
    $mail->addAddress($_SESSION['user_email']);

    $mail->isHTML(true);
    $mail->Subject = "Order Confirmation - ShopHub";

    $mail->Body = "
        <h2>Thank you for your order!</h2>
        <p>Your Order ID: <strong>$order_id</strong></p>
        <p>Total Amount: <strong>₹ $total_amount</strong></p>
        <p>We will notify you once your order is shipped.</p>
        <br>
        <p>Regards,<br>ShopHub Team</p>
    ";

    $mail->send();

} catch (Exception $e) {
    // Do nothing — prevent interruption
}
*/
?>
<div class="alert alert-success">
    <h4>Order placed successfully! 🎉</h4>

    <a href="index.php" class="btn btn-primary mt-3">
        Continue Shopping
    </a>
</div>

<?php require_once("includes/footer.php"); ?>
