<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['add_to_cart'])) {
    $product_id = ($_GET['add_to_cart']);
    

    $check_cart = mysqli_query($db, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($check_cart && mysqli_num_rows($check_cart) > 0) {

        mysqli_query($db, "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {

        mysqli_query($db, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
    
    header("Location: cart.php");
    exit();
}


if (isset($_GET['add_to_wishlist'])) {
    $product_id = intval($_GET['add_to_wishlist']);
    
    $check_wish = mysqli_query($db, "SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($check_wish && mysqli_num_rows($check_wish) == 0) {
        mysqli_query($db, "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)");
    }
    
    header("Location: wishlist.php");
    exit();
}
?>