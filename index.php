<?php
require_once 'config/db.php';

include_once 'includes/header.php';

$sql = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($db, $sql);
?>

<div class="container mt-4">
    
    <div class="hero-banner d-flex align-items-center">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="badge bg-white text-primary fw-bold mb-3 px-3 py-2 rounded-pill">✨ Ramadan Exclusive Sale</span>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="font-weight: 800;">Your Premium Shopping Destination</h1>
                <p class="lead text-white-50 mb-4">Discover curated trends dynamically loaded straight from our master inventories without standard authentication delays.</p>
                <a href="#" class="btn btn-light btn-lg px-4 py-3 fw-bold text-primary border-0 rounded-3 shadow-sm">Explore Collection <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
            <div class="col-md-5 d-none d-md-block text-end">
                <i class="fa-solid fa-store opacity-25" style="font-size: 200px; color: #fff;"></i>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark m-0">🔥 Trending Products</h3>
        <span class="text-muted small fw-semibold">Live Feed Output</span>
    </div>

    <div class="row g-4">
        <?php 
        if ($result && mysqli_num_rows($result) > 0): 
            while ($p = mysqli_fetch_assoc($result)): 
        ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card product-card h-100 position-relative">
                        <span class="product-badge shadow-sm">In Stock: <?= htmlspecialchars($p['stock']) ?></span>
                        
                        <a href="product_details.php?id=<?= $p['id'] ?>" class="text-decoration-none">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 240px; overflow:hidden;">
                                <img src="assets/images/<?= ($p['image']) ?>" class="w-100 h-100" style="object-fit: cover;" alt="Market Product">
                            </div>
                        </a>
                        
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="fw-bold text-dark mb-1 text-truncate" title="<?= ($p['name']) ?>">
                                <a href="product_details.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark hover-primary">
                                    <?= ($p['name']) ?>
                                </a>
                            </h5>
                            <p class="text-muted small text-truncate mb-3"><?= ($p['description']) ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex flex-column">
                                    <small class="text-muted text-uppercase font-monospace" style="font-size: 10px; letter-spacing: 0.5px;">Price</small>
                                    <span class="text-primary fw-bold fs-4">৳<?= ($p['price']) ?></span>
                                </div>
                                <button class="btn btn-modern-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 shadow-sm border-0">
                    <i class="fa-regular fa-folder-open text-muted mb-3" style="font-size: 60px;"></i>
                    <p class="text-muted fs-5 mb-0">No active records stored inside current inventories yet.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
include_once 'includes/footer.php'; 
?>