<?php
require_once("config/db.php");
session_start();

$error = "";

if(isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {

        // Check if user is blocked
        if(isset($user['status']) && $user['status'] === 'Blocked'){
            $error = "Your account has been blocked. Please contact admin.";
        }

        // Verify password
        elseif(password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: index.php");
            exit;

        } else {
            $error = "Invalid email or password";
        }

    } else {
        $error = "Invalid email or password";
    }
}

require_once("includes/header.php");
?>

<div class="container mt-5" style="max-width:500px;">
<h2 class="mb-4">Login</h2>

<?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3 text-end">
        <a href="forgot_password.php">Forgot Password?</a>
    </div>

    <button type="submit" name="login" class="btn btn-success w-100">
        Login
    </button>

</form>
</div>

<?php require_once("includes/footer.php"); ?>
