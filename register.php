<?php
require_once("config/db.php");
session_start();

$error = "";

if(isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* CHECK IF EMAIL EXISTS */
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);

    if($check->rowCount() > 0){
        $error = "Email already registered. Please login.";
    } else {

        $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $stmt->execute([$name,$email,$password]);

        header("Location: login.php");
        exit;
    }
}

require_once("includes/header.php");
?>

<h2>Register</h2>

<?php if($error): ?>
<div class="alert alert-danger">
    <?php echo $error; ?>
</div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" name="register" class="btn btn-primary">
        Register
    </button>
</form>

<?php require_once("includes/footer.php"); ?>
