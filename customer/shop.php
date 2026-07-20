<?php 
require_once '../config/db.php'; 
include_once '../includes/header.php'; 
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 mb-4 rounded-4 border-0">
                <h5 class="fw-bold mb-3">Categories</h5>
                <div class="list-group list-group-flush">
                    <a href="shop.php" class="list-group-item list-group-item-action fw-bold text-primary">All Products</a>
                    <?php
                    // ক্যাটাগরি এবং সাব-ক্যাটাগরি লুপ
                    $types = mysqli_query($db, "SELECT * FROM product_types");
                    while ($type = mysqli_fetch_assoc($types)) {
                        echo '<div class="mt-3 fw-bold text-uppercase small text-muted">'.$type['type_name'].'</div>';
                        $subs = mysqli_query($db, "SELECT * FROM sub_categories WHERE product_type_id = '".$type['id']."'");
                        while ($sub = mysqli_fetch_assoc($subs)) {
                            // Active ক্লাস দেওয়ার লজিক
                            $active = (isset($_GET['sub_id']) && $_GET['sub_id'] == $sub['id']) ? 'active' : '';
                            echo '<a href="shop.php?sub_id='.$sub['id'].'" class="list-group-item list-group-item-action ps-3 border-0 '.$active.'">'.$sub['name'].'</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row g-4">
                <?php
                // ফিল্টার অনুযায়ী কুয়েরি
                $sql = isset($_GET['sub_id']) ? "SELECT * FROM products WHERE sub_category_id = '".mysqli_real_escape_string($db, $_GET['sub_id'])."'" : "SELECT * FROM products";
                $result = mysqli_query($db, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($p = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="card product-card h-100 position-relative shadow-sm border-0" style="border-radius: 12px; overflow: hidden; background: #fff;">
                                
                                <a href="action_handler.php?add_to_wishlist=<?= $p['id'] ?>" class="position-absolute end-0 top-0 p-3 text-danger fs-5" style="z-index: 10;" title="Add to Wishlist">
                                    <i class="fa-regular fa-heart"></i>
                                </a>

                                <a href="../product_details.php?id=<?= $p['id'] ?>" class="text-decoration-none">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 240px; overflow:hidden;">
                                        <img src="../assets/images/<?= htmlspecialchars($p['image']) ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?= htmlspecialchars($p['name']) ?>">
                                    </div>
                                </a>
                                
                                <div class="card-body d-flex flex-column p-3">
                                    <h5 class="fw-bold text-dark mb-1 text-truncate">
                                        <a href="../product_details.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($p['name']) ?></a>
                                    </h5>
                                    <p class="text-muted small text-truncate mb-3"><?= htmlspecialchars($p['description']) ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="text-primary fw-bold fs-5">৳<?= number_format($p['price'], 2) ?></span>
                                        
                                        <?php if(isset($p['stock']) && $p['stock'] > 0): ?>
                                            <a href="action_handler.php?add_to_cart=<?= $p['id'] ?>" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;" title="Add to Cart">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;" disabled title="Out of Stock">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php 
                    } 
                } else {
                    echo "<div class='col-12 text-center py-5'><p class='text-muted'>No products found in this category.</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>