<?php
require_once("config/db.php");
include "includes/header.php";

if(!isset($_GET['token'])){
    die("Invalid request.");
}

$token = $_GET['token'];
$current_time = date("Y-m-d H:i:s");

$stmt = $conn->prepare("
    SELECT id 
    FROM users 
    WHERE reset_token=? 
    AND token_expiry > ?
    
");
// AND token_expiry > NOW()
//$stmt->execute([$token]);
$stmt->execute([$token, $current_time]);

if($stmt->rowCount() == 0){
    die("Invalid or expired token.");
}

$error = "";
$success = "";

if(isset($_POST['reset'])){

    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if($password !== $confirm){
        $error = "Passwords do not match.";
    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("
            UPDATE users 
            SET password=?, reset_token=NULL, token_expiry=NULL 
            WHERE reset_token=?
        ");
        $update->execute([$hash, $token]);

        $success = "Password reset successful. You can now login.";
    }
}
?>

<div class="container mt-5" style="max-width:500px;">
<h3>Reset Password</h3>

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
<div class="mb-3">
<label>New Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3">
<label>Confirm Password</label>
<input type="password" name="confirm" class="form-control" required>
</div>

<button type="submit" name="reset" class="btn btn-primary w-100">
Reset Password
</button>
</form>
</div>

<?php include "includes/footer.php"; ?>
