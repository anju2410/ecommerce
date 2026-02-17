<?php
require "config/db.php";

if(isset($_POST['category_id'])){

    $stmt = $pdo->prepare("SELECT * FROM subcategories WHERE category_id=?");
    $stmt->execute([$_POST['category_id']]);
    $subcategories = $stmt->fetchAll();

    foreach($subcategories as $sub){
        echo "<option value='".$sub['id']."'>".$sub['name']."</option>";
    }
}
?>
