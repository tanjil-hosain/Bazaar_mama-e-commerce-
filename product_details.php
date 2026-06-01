<?php
session_start();
require_once 'config/db.php';

// ১. ইউআরএল থেকে প্রোডাক্টের আইডি নেওয়া
$id = $_GET['id'];

// ২. ডাটাবেজ থেকে মূল প্রোডাক্টের ডেটা আনা
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = mysqli_query($db, $sql);
$product = mysqli_fetch_assoc($result);

// ৩. প্রোডাক্টের ক্যাটাগরি আইডি দিয়ে ক্যাটাগরির নাম খুঁজে বের করা
$cat_id = $product['category_id'];
$cat_sql = "SELECT * FROM categories WHERE id = '$cat_id'";
$cat_result = mysqli_query($db, $cat_sql);
$category = mysqli_fetch_assoc($cat_result);

// ৪. প্রোডাক্টের সাব-ক্যাটাগরি আইডি দিয়ে সাব-ক্যাটাগরির নাম খুঁজে বের করা
$sub_cat_id = $product['sub_category_id'];
$sub_cat_sql = "SELECT * FROM sub_categories WHERE id = '$sub_cat_id'";
$sub_cat_result = mysqli_query($db, $sub_cat_sql);
$sub_category = mysqli_fetch_assoc($sub_cat_result);

include_once 'includes/header.php';
?>

<style>
    body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
    .glass-card { background: #ffffff; border: none; border-radius: 24px; box-shadow: 0 12px 40px rgba(0,0,0,0.03); overflow: hidden; }
    .preview-box { background-color: #f1f5f9; border-radius: 16px; min-height: 400px; display: flex; align-items: center; justify-content: center; position: relative; }
    .product-main-img { max-width: 100%; max-height: 380px; object-fit: contain; transition: transform 0.3s ease; }
    .product-main-img:hover { transform: scale(1.03); }
    .meta-badge { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; padding: 6px 14px; border-radius: 50px; text-transform: uppercase; }
    .price-display { font-size: 2.2rem; font-weight: 800; color: #0984e3; }
    .spec-pill { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; }
    .btn-checkout { background-color: #0984e3; border: none; font-weight: 700; padding: 14px 28px; border-radius: 12px; transition: all 0.2s ease; color: #fff; }
    .btn-checkout:hover { background-color: #076bba; color: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(9, 132, 227, 0.25); }
</style>

<div class="container my-5">
    
    <!-- উপরের নেভিগেশন বাটন -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php" class="btn btn-outline-dark border-0 fw-bold px-3 py-2 rounded-3" style="background: #e2e8f0;">
            <i class="fa-solid fa-arrow-left-long me-2"></i> Continue Shopping
        </a>
    </div>

    <!-- মেইন প্রোডাক্ট কার্ড (গ্লাস কার্ড স্টাইল) -->
    <div class="card glass-card p-4 p-md-5">
        <div class="row g-5">
            
            <!-- বাম পাশে প্রোডাক্টের ছবি এবং স্টক স্ট্যাটাস -->
            <div class="col-lg-6">
                <div class="preview-box border p-4 shadow-sm">
                    <?php if($product['stock'] > 0): ?>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-success text-white px-3 py-2 rounded-pill fw-bold" style="z-index: 5;"><i class="fa-solid fa-circle-check me-1"></i> In Stock</span>
                    <?php else: ?>
                        <span class="position-absolute top-0 start-0 m-3 badge bg-danger text-white px-3 py-2 rounded-pill fw-bold" style="z-index: 5;"><i class="fa-solid fa-circle-xmark me-1"></i> Stock Out</span>
                    <?php endif; ?>

                    <img src="assets/images/<?php echo $product['image']; ?>" alt="Product Image" class="product-main-img img-fluid">
                </div>
            </div>

            <!-- ডান পাশে প্রোডাক্টের ডিটেইলস -->
            <div class="col-lg-6 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex gap-2 mb-3 align-items-center">
                        <span class="badge bg-primary-subtle text-primary meta-badge">Standard Product</span>
                        <span class="badge bg-dark text-white meta-badge">SKU: <?php echo $product['sku']; ?></span>
                    </div>

                    <!-- প্রোডাক্টের নাম -->
                    <h1 class="fw-extrabold text-dark mb-2" style="font-weight: 800; font-size: 2.2rem;"><?php echo $product['name']; ?></h1>
                    
                    <!-- প্রোডাক্টের দাম -->
                    <div class="d-flex align-items-baseline gap-2 my-4">
                        <span class="price-display">৳<?php echo $product['price']; ?></span>
                        <span class="text-muted font-monospace small">/ Per Item</span>
                    </div>

                    <hr class="opacity-25 my-4">

                    <!-- গ্রিডের ভেতরে ডায়নামিক ক্যাটাগরি এবং সাব-ক্যাটাগরির নাম -->
                    <div class="row g-3 mb-4">
                        <!-- ক্যাটাগরির নাম -->
                        <div class="col-md-6">
                            <div class="spec-pill d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-tags"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight:600; text-transform: uppercase;">Category</small>
                                    <span class="fw-bold text-dark"><?php echo $category['name']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- সাব-ক্যাটাগরির নাম -->
                        <div class="col-md-6">
                            <div class="spec-pill d-flex align-items-center gap-3">
                                <div class="bg-info text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-folder-tree"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight:600; text-transform: uppercase;">Sub-Category</small>
                                    <span class="fw-bold text-dark"><?php echo $sub_category['name']; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- এভেইলএবল স্টক -->
                        <div class="col-md-6">
                            <div class="spec-pill d-flex align-items-center gap-3">
                                <div class="bg-success text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px; font-weight:600; text-transform: uppercase;">Available Stock</small>
                                    <span class="fw-bold text-dark"><?php echo $product['stock']; ?> Pcs</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- প্রোডাক্টের ডেসক্রিপশন -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-file-lines text-muted me-2"></i>Product Overview:</h6>
                        <p class="text-secondary lh-lg bg-light p-3 rounded-3 border-start border-primary border-4" style="white-space: pre-line; font-size: 0.95rem;">
                            <?php echo $product['description']; ?>
                        </p>
                    </div>
                </div>

            
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <div class="input-group d-none d-sm-flex" style="max-width: 130px;">
                            <button class="btn btn-outline-secondary border-secondary-subtle" type="button" onclick="let x = document.getElementById('qty'); if(q.value > 1) q.value--;"><i class="fa-solid fa-minus"></i></button>
                            <input type="text" id="qty" class="form-control text-center bg-transparent border-secondary-subtle fw-bold" value="1">
                            <button class="btn btn-outline-secondary border-secondary-subtle" type="button" onclick="let y = document.getElementById('qty'); q.value++;"><i class="fa-solid fa-plus"></i></button>
                        </div>
                        <button class="btn btn-checkout flex-grow-1 btn-lg shadow-sm">
                            <i class="fa-solid fa-cart-shopping me-2"></i> Add To Bazaar Basket
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php 
include_once 'includes/footer.php'; 
?>