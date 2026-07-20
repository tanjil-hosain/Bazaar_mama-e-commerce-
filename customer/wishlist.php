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

if (isset($_GET['remove_wish_id'])) {
    $wish_id = ($_GET['remove_wish_id']);
    mysqli_query($db, "DELETE FROM wishlist WHERE id = $wish_id AND user_id = $user_id");
    $msg = "Item removed from Wishlist!";
}
$wish_result = mysqli_query($db, "SELECT wishlist.id as wish_id, products.* FROM wishlist 
                                   JOIN products ON wishlist.product_id = products.id 
                                   WHERE wishlist.user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - My Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .wish-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; }
        .card-custom { border: none; border-radius: 16px; background: #ffffff; }
    </style>
</head>
<body >
    <?php
    include_once('../includes/header.php')
    ?>

    <div class="container" style="max-width: 850px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark m-0"><i class="fa-solid fa-heart text-danger me-2"></i> My Wishlist</h3>
            <a href="../index.php" class="btn btn-outline-dark fw-bold border-0"><i class="fa-solid fa-arrow-left me-2"></i> Back to Shop</a>
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
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($wish_result && mysqli_num_rows($wish_result) > 0):
                            while ($row = mysqli_fetch_assoc($wish_result)):
                        ?>
                                <tr>
                                    
                                    <td>
                                        <?php if(!empty($row['image'])): ?>
                                            <img src="../assets/images/<?= $row['image'] ?>" class="wish-img border">
                                        <?php else: ?>
                                            <div class="wish-img border bg-light d-flex align-items-center justify-content-center text-muted small">No Img</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-secondary"><?= ($row['name']) ?></td>
                                    <td class="fw-bold text-dark">৳<?= number_format($row['price'], 2) ?></td>
                                    
                                    <td class="text-center">
                                        <a href="action_handler.php?add_to_cart=<?= $row['id'] ?>" class="btn btn-sm btn-success me-2 fw-semibold shadow-sm px-3">
                                            <i class="fa-solid fa-cart-plus me-1"></i> Add To Cart
                                        </a>
                                        <a href="wishlist.php?remove_wish_id=<?= $row['wish_id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Remove from wishlist?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php 
                            endwhile;
                        else:
                            echo "<tr><td colspan='4' class='text-center text-muted py-5'><i class=".'"fa-regular fa-heart mb-2 d-block fa-2x"'."></i>Your wishlist is empty, Mama! Save some products for later.</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php 
    include_once('../includes/footer.php')
     ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>