<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("config/db.php");

// Detect current category/subcategory
$current_category = $_GET['id'] ?? null;
$current_page = basename($_SERVER['PHP_SELF']);

// Cart Count
$cart_count = 0;

if(isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($result['total'])) {
        $cart_count = $result['total'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ShopHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }

.navbar-brand { font-weight:700; font-size:22px; }

.category-bar {
    background:white;
    border-top:1px solid #ddd;
    border-bottom:1px solid #ddd;
}

.category-bar .nav-link {
    font-weight:600;
    color:#000;
}

.category-bar .nav-link.active {
    color:#0d6efd !important;
}

.dropdown:hover .dropdown-menu {
    display:block;
}

.product-card {
    transition: all 0.3s ease;
    border-radius:10px;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow:0 6px 20px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark py-3">
<div class="container">
    <a class="navbar-brand" href="index.php">🛍 ShopHub</a>

    <form class="d-flex mx-auto" action="search.php" method="GET" style="width:50%;">
        <input class="form-control me-2" type="search" name="keyword" placeholder="Search products..." required>
        <button class="btn btn-warning">Search</button>
    </form>

    <ul class="navbar-nav flex-row align-items-center">

        <li class="nav-item me-3">
            <a class="nav-link text-white" href="cart.php">
                🛒 Cart
                <?php if($cart_count > 0): ?>
                    <span class="badge bg-danger rounded-pill">
                        <?php echo $cart_count; ?>
                    </span>
                <?php endif; ?>
            </a>
        </li>

        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item me-2 text-warning">
                Hello, <?php echo $_SESSION['user_name']; ?>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout.php">Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item me-2">
                <a class="nav-link text-white" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="register.php">Register</a>
            </li>
        <?php endif; ?>

    </ul>
</div>
</nav>

<!-- CATEGORY MENU -->
<div class="category-bar">
<div class="container">
<ul class="nav py-2">

<?php
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

foreach($categories as $cat):

$sub_stmt = $conn->prepare("SELECT * FROM subcategories WHERE category_id = ?");
$sub_stmt->execute([$cat['id']]);
$subcategories = $sub_stmt->fetchAll(PDO::FETCH_ASSOC);

$is_active = ($current_page == "category.php" && $current_category == $cat['id']) ? "active" : "";
?>

<li class="nav-item dropdown me-4">
    <a class="nav-link dropdown-toggle <?php echo $is_active; ?>"
       href="category.php?id=<?php echo $cat['id']; ?>">
       <?php echo $cat['name']; ?>
    </a>

    <?php if(count($subcategories) > 0): ?>
    <ul class="dropdown-menu">
        <?php foreach($subcategories as $sub): ?>
            <li>
                <a class="dropdown-item"
                   href="subcategory.php?id=<?php echo $sub['id']; ?>">
                   <?php echo $sub['name']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</li>

<?php endforeach; ?>

</ul>
</div>
</div>

<div class="container mt-4">
