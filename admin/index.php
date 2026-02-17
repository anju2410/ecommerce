<?php
require_once("../config/db.php");
session_start();

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if($admin && password_verify($password, $admin['password'])){

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#667eea,#764ba2);
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}
.login-card{
    width:400px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}
</style>

</head>
<body>

<div class="card login-card p-4">
    <h3 class="text-center mb-4">Admin Login</h3>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" 
                   name="password" 
                   class="form-control" 
                   required>
        </div>

        <button type="submit" 
                name="login" 
                class="btn btn-dark w-100">
            Login
        </button>

    </form>

    <div class="text-center mt-3 text-muted">
        Ecommerce Admin Panel
    </div>
</div>

</body>
</html>
