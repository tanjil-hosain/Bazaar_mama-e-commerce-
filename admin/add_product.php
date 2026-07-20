<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';

$categories_result     = mysqli_query($db, "SELECT * FROM categories");
$product_types_result  = mysqli_query($db, "SELECT * FROM product_types");
$sub_categories_result = mysqli_query($db, "SELECT * FROM sub_categories");
$units_result          = mysqli_query($db, "SELECT * FROM product_units");

$msg = ""; 
$type = "";

if (isset($_POST['add_prdct'])) {
    
    $name            =  $_POST['name'];
    $description     =  $_POST['description'];
    $sku             =  $_POST['sku'];
    $buying_price    =  $_POST['buying_price']; 
    $price           =  $_POST['price'];       
    $stock           =  $_POST['stock'];
    $category_id     =  $_POST['category_id'];
    $product_type_id =  $_POST['product_type_id'];
    $sub_category_id =  $_POST['sub_category_id'];
    $unit_id         =  $_POST['unit_id'];
    
    //img upload
    $image = isset($_FILES['image']['name']) ? mysqli_real_escape_string($db, $_FILES['image']['name']) : '';
    $target_dir = "../assets/images/";
    
    if (!empty($image)) {
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // পিএইচপি ইনসার্ট কুয়েরি (buying_price সহ আপডেট করা হয়েছে)
    $sql = "INSERT INTO products (name, description, sku, price, buying_price, stock, image, category_id, product_type_id, sub_category_id, unit_id) 
            VALUES ('$name', '$description', '$sku', $price, $buying_price, $stock, '$image', '$category_id', '$product_type_id', '$sub_category_id', '$unit_id')";
    
    if (mysqli_query($db, $sql)) {
        $msg = "Dynamic Product Synced Successfully with Buying Price, Mama!";
        $type = "success";
    } else {
        $msg = "❌ Insertion Failed! Error: " . mysqli_error($db);
        $type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .product-form-card { border: none; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.04); background: #ffffff; }
        .form-select, .form-control { border-radius: 8px; padding: 10px 12px; }
        .form-select:focus, .form-control:focus { border-color: #0984e3; box-shadow: 0 0 0 3px rgba(9, 132, 227, 0.1); }
    </style>
</head>
<body class="py-5">

    <div class="container" style="max-width: 850px;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                <a href="dashboard.php" class="btn btn-outline-dark border-0 fw-bold">
                    <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard
                </a>
                <a href="../index.php" target="_blank" class="btn btn-outline-info fw-bold text-dark">
                    <i class="fa-solid fa-globe me-2"></i> Visit Site
                </a>
            </div>
            <span class="text-muted small font-monospace">Products Engine</span>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card product-form-card">
            <div class="card-body p-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-box-open fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark m-0">Upload Product</h4>
                        <p class="text-muted small m-0">Fill in details matching your e-commerce 12-field database schema</p>
                    </div>
                </div>

                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark small">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Premium Smart Watch" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark small">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Write full specifications of the items..."></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark small">SKU (Unique Code)</label>
                            <input type="text" name="sku" class="form-control" placeholder="e.g. BZ-WATCH-02" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark small">Buying Price (BDT)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">৳</span>
                                <input type="number" step="0.01" name="buying_price" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark small">Selling Price (BDT)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">৳</span>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark small">Stock Volume</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Category Mapping</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                <?php 
                                while($cat = mysqli_fetch_assoc($categories_result)) {
                                    $cat_name = isset($cat['category_name']) ? $cat['category_name'] : (isset($cat['name']) ? $cat['name'] : 'Category #'.$cat['id']);
                                    echo "<option value='".$cat['id']."'>".($cat_name)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Product Type Connection</label>
                            <select name="product_type_id" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                <?php 
                                while($pt =  mysqli_fetch_assoc($product_types_result)) {
                                    echo "<option value='".$pt['id']."'>".($pt['type_name'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Sub-Category Relation</label>
                            <select name="sub_category_id" class="form-select" required>
                                <option value="">-- Select Sub-Category --</option>
                                <?php 
                                while($subcat = mysqli_fetch_assoc($sub_categories_result)) {
                                    $subcat_name = isset($subcat['sub_category_name']) ? $subcat['sub_category_name'] : (isset($subcat['name']) ? $subcat['name'] : 'Sub-Category #'.$subcat['id']);
                                    echo "<option value='".$subcat['id']."'>".($subcat_name)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Product Unit Standard</label>
                            <select name="unit_id" class="form-select" required>
                                <option value="">-- Select Unit Type --</option>
                                <?php 
                                while($u = mysqli_fetch_assoc($units_result)) {
                                    echo "<option value='".$u['id']."'>".($u['unit_name'])." (".($u['short_name']).")</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold text-dark small">Product Showcase Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <div class="form-text text-muted">Upload high-res images. Destination node targets `assets/images/` path.</div>
                    </div>

                    <button type="submit" name="add_prdct" class="btn btn-primary btn-lg w-100 fw-bold text-white rounded-3 shadow-sm py-3" style="background-color: #0984e3; border: none;">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Launch Product to Showcase Portal
                    </button>

                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>