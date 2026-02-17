<?php
session_start();
require_once("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=? AND product_id=?");
$stmt->execute([$user_id,$product_id]);

if($stmt->rowCount()>0){
    $conn->prepare("UPDATE cart SET quantity=quantity+1 
    WHERE user_id=? AND product_id=?")
    ->execute([$user_id,$product_id]);
} else {
    $conn->prepare("INSERT INTO cart(user_id,product_id,quantity)
    VALUES(?,?,1)")
    ->execute([$user_id,$product_id]);
}

header("Location: ".$_SERVER['HTTP_REFERER']);
