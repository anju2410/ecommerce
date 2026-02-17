<?php
require_once("config/db.php");
session_start();

$error = "";

if(isset($_POST['register'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Check empty fields
    if(empty($name) || empty($email) || empty($password) || empty($confirm)){
        $error = "All fields are required.";
    }

    // Check password match
    elseif($password !== $confirm){
        $error = "Passwords do not match.";
    }

    else {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->execute([$email]);

        if($check->rowCount() > 0){
            $error = "Email already registered. Please login.";
        } 
        else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $stmt->execute([$name,$email,$hashedPassword]);

            header("Location: login.php");
            exit;
        }
    }
}

require_once("includes/header.php");
?>

<div class="container mt-4" style="max-width:500px;">
<h3 class="mb-4">Create Account</h3>

<?php if($error): ?>
<div class="alert alert-danger">
    <?php echo $error; ?>
</div>
<?php endif; ?>

<form method="POST" autocomplete="off">

    <div class="mb-3">
        <label>Name</label>
        <input type="text" 
               name="name" 
               class="form-control" 
               required 
               autocomplete="off">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" 
               name="email" 
               class="form-control" 
               required 
               autocomplete="off">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" 
               name="password" 
               class="form-control" 
               required 
               autocomplete="new-password">
    </div>

    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" 
               name="confirm_password" 
               class="form-control" 
               required 
               autocomplete="new-password">
    </div>

    <button type="submit" name="register" class="btn btn-primary w-100">
        Register
    </button>

</form>
</div>

<?php require_once("includes/footer.php"); ?>
