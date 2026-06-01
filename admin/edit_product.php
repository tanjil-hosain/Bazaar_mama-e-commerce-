<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}

require_once '../config/db.php';

$msg = ""; 
$type = "";

if (!isset($_GET['id'])) { 
    header("Location: manage_products.php"); 
    exit(); 
}
$id = ($_GET['id']); 
$categories_result     = mysqli_query($db, "SELECT * FROM categories");
$product_types_result  = mysqli_query($db, "SELECT * FROM product_types");
$sub_categories_result = mysqli_query($db, "SELECT * FROM sub_categories");
$units_result          = mysqli_query($db, "SELECT * FROM product_units");

$product_query = mysqli_query($db, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($product_query);

if (!$product) { 
    header("Location: manage_products.php"); 
    exit(); 
}

if (isset($_POST['edit'])) {
    $name            = ($_POST['name']);
    $description     =($_POST['description']);
    $sku             =($_POST['sku']);
    $price           =($_POST['price']);
    $stock           = ($_POST['stock']);
    $category_id     = ($_POST['category_id']);
    $product_type_id = ($_POST['product_type_id']);
    $sub_category_id = ($_POST['sub_category_id']);
    $unit_id         =  ($_POST['unit_id']);

    $image = $product['image']; 
    

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
  
        if(!empty($product['image']) && file_exists("../".$product['image'])) {
            unlink("../".$product['image']);
        }
        
        $image = "assets/images/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image);
    }


    $sql = "UPDATE products SET 
                name = '$name', 
                description = '$description', 
                sku = '$sku', 
                price = $price, 
                stock = $stock, 
                image = '$image', 
                category_id = '$category_id', 
                product_type_id = '$product_type_id', 
                sub_category_id = '$sub_category_id', 
                unit_id = '$unit_id' 
            WHERE id = $id";
    
    if (mysqli_query($db, $sql)) {
        $msg = " Product Updated Successfully, Mama!";
        $type = "success";
        
        // পেজে যাতে তৎক্ষণাৎ নতুন ইমেজটা দেখা যায় তার জন্য লোকাল ভেরিয়েবল আপডেট
        $product['image'] = $image;
    } else {
        $msg = "❌ Update Failed! Error: " . mysqli_error($db);
        $type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>BazaarMama - Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 850px;">
        <div class="mb-4">
            <a href="manage_products.php" class="btn btn-outline-dark border-0 fw-bold"><i class="fa-solid fa-arrow-left me-2"></i> Back to Inventory</a>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 bg-white p-5">
            <h4 class="fw-bold text-dark mb-4"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Product Details</h4>
            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Product Name</label>
                    <input type="text" name="name" class="form-control" value="<?= ($product['name'] ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= ($product['description'] ?? '') ?></textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">SKU</label>
                        <input type="text" name="sku" class="form-control" value="<?= ($product['sku'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Price (BDT)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?? 0 ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Stock Volume</label>
                        <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?? 0 ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Category Mapping</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <?php 
                            while($cat = mysqli_fetch_assoc($categories_result)) {
                                $cat_name = isset($cat['category_name']) ? $cat['category_name'] : ($cat['name'] ?? 'Category #'.$cat['id']);
                                $selected = ($product['category_id'] == $cat['id']) ? 'selected' : '';
                                echo "<option value='".$cat['id']."' $selected>".($cat_name)."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Product Type Connection</label>
                        <select name="product_type_id" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <?php 
                            while($pt = mysqli_fetch_assoc($product_types_result)) {
                                $selected = ($product['product_type_id'] == $pt['id']) ? 'selected' : '';
                                echo "<option value='".$pt['id']."' $selected>".($pt['type_name'])."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Sub-Category</label>
                        <select name="sub_category_id" class="form-select" required>
                            <option value="">-- Select Sub-Category --</option>
                            <?php 
                            while($subcat = mysqli_fetch_assoc($sub_categories_result)) {
                                $subcat_name = isset($subcat['sub_category_name']) ? $subcat['sub_category_name'] : ($subcat['name'] ?? 'Sub-Category #'.$subcat['id']);
                                $selected = ($product['sub_category_id'] == $subcat['id']) ? 'selected' : '';
                                echo "<option value='".$subcat['id']."' $selected>".($subcat_name)."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Product Unit</label>
                        <select name="unit_id" class="form-select" required>
                            <option value="">-- Select Unit --</option>
                            <?php 
                            while($u = mysqli_fetch_assoc($units_result)) {
                                $selected = ($product['unit_id'] == $u['id']) ? 'selected' : '';
                                echo "<option value='".$u['id']."' $selected>".($u['unit_name'])."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-semibold small">Product Showcase Image (Leave empty to keep current)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="mt-2">
                        Current: 
                        <?php if(!empty($product['image'])): ?>
                            <img src="../<?= ($product['image']) ?>" width="60" class="border rounded">
                        <?php else: ?>
                            <span class="text-muted small">No image uploaded</span>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" name="edit" class="btn btn-primary btn-lg w-100 fw-bold">Update Product</button>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>