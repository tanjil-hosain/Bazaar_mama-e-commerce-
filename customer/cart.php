<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';
$user_id = $_SESSION['user_id'];
$msg = "";

if (isset($_GET['remove_id'])) {
    $remove_id = intval($_GET['remove_id']);
    mysqli_query($db, "DELETE FROM cart WHERE id = $remove_id AND user_id = $user_id");
    $msg = "Item removed from cart, Mama!";
}

if (isset($_GET['update_cart_id']) && isset($_GET['action'])) {
    $cart_id = intval($_GET['update_cart_id']);
    $action = $_GET['action'];
    
    if ($action == 'increase') {
        mysqli_query($db, "UPDATE cart SET quantity = quantity + 1 WHERE id = $cart_id AND user_id = $user_id");
    } elseif ($action == 'decrease') {
        mysqli_query($db, "UPDATE cart SET quantity = quantity - 1 WHERE id = $cart_id AND user_id = $user_id AND quantity > 1");
    }
    header("Location: cart.php");
    exit();
}

$cart_result = mysqli_query($db, "SELECT cart.id as cart_id, cart.quantity, products.* FROM cart 
                                   JOIN products ON cart.product_id = products.id 
                                   WHERE cart.user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .cart-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
        .card-custom { border: none; border-radius: 16px; background: #ffffff; }
    </style>
</head>
<body class="py-5">

    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark m-0"><i class="fa-solid fa-basket-shopping text-primary me-2"></i> My Cart</h3>
            <a href="../index.php" class="btn btn-outline-dark fw-bold border-0"><i class="fa-solid fa-arrow-left me-2"></i> Continue Shopping</a>
        </div>

        
        <?php if(!empty($msg)): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4"><i class="fa-solid fa-circle-check me-2"></i><?= $msg ?></div>
        <?php endif; ?>

        <div class="card card-custom shadow-sm p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th class="text-center">Quantity</th>
                            <th>Subtotal</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        if ($cart_result && mysqli_num_rows($cart_result) > 0):
                            while ($item = mysqli_fetch_assoc($cart_result)):
                                $subtotal = $item['price'] * $item['quantity'];
                                $grand_total += $subtotal;
                        ?>
                                <tr>
                                    <td>
                                        <?php if(!empty($item['image'])): ?>
                                            <img src="../assets/images/<?= $item['image'] ?>" class="cart-img border">
                                        <?php else: ?>
                                            <div class="cart-img border bg-light d-flex align-items-center justify-content-center text-muted small">No Img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-secondary"><?= ($item['name']) ?></td>
                                    <td class="fw-semibold">৳<?= number_format($item['price'], 2) ?></td>
                                  <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="cart.php?update_cart_id=<?= $item['cart_id'] ?>&action=decrease" class="btn btn-sm btn-light border px-2 fw-bold">-</a>
                                            <span class="fw-bold px-1" style="min-width: 20px;"><?= $item['quantity'] ?></span>
                                            <a href="cart.php?update_cart_id=<?= $item['cart_id'] ?>&action=increase" class="btn btn-sm btn-light border px-2 fw-bold">+</a>
                                        </div>
                                    </td>
                                    
                                    <td class="fw-bold text-primary">৳<?= number_format($subtotal, 2) ?></td>
                                    <td class="text-center">
                                        <a href="cart.php?remove_id=<?= $item['cart_id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Remove this item, Mama?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php 
                            endwhile;
                        ?>                  
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold fs-5 py-3">Grand Total:</td>
                                <td colspan="2" class="fw-bold text-success fs-4 py-3">৳<?= number_format($grand_total, 2) ?></td>
                            </tr>
                        <?php
                        else:
                            echo "<tr><td colspan='6' class='text-center text-muted py-5'><i class=".'"fa-solid fa-folder-open mb-2 d-block fa-2x"'."></i>Your cart is empty, Mama! Add some premium products.</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($grand_total > 0): ?>
                <div class="text-end mt-4">
                    <a href="checkout.php" class="btn btn-primary btn-lg fw-bold px-5 py-2.5 shadow-sm" style="background: #0984e3; border: none; border-radius: 10px;">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>