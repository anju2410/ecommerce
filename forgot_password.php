<?php
require_once("config/db.php");
include "includes/header.php";

$message = "";
$link = "";

if(isset($_POST['forgot'])){

    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);

    if($stmt->rowCount() > 0){

        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("
            UPDATE users 
            SET reset_token=?, token_expiry=? 
            WHERE email=?
        ");
        $update->execute([$token, $expiry, $email]);

        $link = "http://localhost/ecommerce/reset_password.php?token=" . $token;

        $message = "Reset link generated (valid for 1 hour):";
    } else {
        $message = "Email not found.";
    }
}
?>

<div class="container mt-5" style="max-width:500px;">
<h3>Forgot Password</h3>

<?php if($message): ?>
<div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<?php if($link): ?>
<div class="alert alert-success" style="word-break:break-all;">
<a href="<?= $link ?>"><?= $link ?></a>
</div>
<?php endif; ?>

<form method="POST">
<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<button type="submit" name="forgot" class="btn btn-primary w-100">
Generate Reset Link
</button>
</form>
</div>

<?php include "includes/footer.php"; ?>
