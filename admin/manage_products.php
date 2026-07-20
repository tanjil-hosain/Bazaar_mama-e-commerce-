<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}

require_once '../config/db.php';

$msg = "";


if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $img_query = mysqli_query($db, "SELECT image FROM products WHERE id = $delete_id");
    $prod = mysqli_fetch_assoc($img_query);
    

    if($prod && !empty($prod['image']) && file_exists("../".$prod['image'])) {
        unlink("../".$prod['image']);
    }

  
    $delete_sql = "DELETE FROM products WHERE id = $delete_id";
    if (mysqli_query($db, $delete_sql)) {
        $msg = " Product removed successfully!";
    }
}

$products_result = mysqli_query($db, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Available Products - BazaarMama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .main-content { margin-left: 260px; padding: 40px; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
     <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h3 class="fw-bold text-dark mb-4"><i class="fa-solid fa-boxes-stacked text-success me-2"></i>Stock Inventory</h3>
        
        <?php if(!empty($msg)): ?><div class="alert alert-success border-0 shadow-sm"><?= $msg ?></div><?php endif; ?>

        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Image</th><th>Title</th><th>Type</th><th>Category</th><th>Price</th><th>Unit</th><th class="text-center">Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($products_result && mysqli_num_rows($products_result) > 0) {
                            while ($p = mysqli_fetch_assoc($products_result)) {
                                
                                $type_name  = "N/A";
                                $cat_name   = "N/A";
                                $short_name = "N/A";

                                if (!empty($p['product_type_id'])) {
                                    $t_res = mysqli_query($db, "SELECT type_name FROM product_types WHERE id = " . $p['product_type_id']);
                                    if ($t_row = mysqli_fetch_assoc($t_res)) $type_name = $t_row['type_name'];
                                }
                                //catagory                  
                                if (!empty($p['category_id'])) {
                                    $c_res = mysqli_query($db, "SELECT  name FROM categories WHERE id = " . $p['category_id']);
                                    if ($c_row = mysqli_fetch_assoc($c_res)) {
                                        $cat_name = isset($c_row['category_name']) ? $c_row['category_name'] : ($c_row['name'] ?? 'N/A');
                                    }
                                }
                                //unit

                                if (!empty($p['unit_id'])) {
                                    $u_res = mysqli_query($db, "SELECT short_name FROM product_units WHERE id = " . $p['unit_id']);
                                    if ($u_row = mysqli_fetch_assoc($u_res)) $short_name = $u_row['short_name'];
                                }
                        ?>
                                <tr>
                                    <td>
                                        <?php if(!empty($p['image'])): ?>
                                            <img src="../<?= ($p['image']) ?>" class="product-img border">
                                        <?php else: ?>
                                            <div class="product-img border bg-light d-flex align-items-center justify-content-center text-muted small">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-secondary"><?= ($p['name']) ?></td>
                                    <td><span class="badge bg-info-subtle text-info"><?= ($type_name) ?></span></td>
                                    <td><?= ($cat_name) ?></td>
                                    <td class="fw-bold">৳ <?= number_format($p['price'], 2) ?></td>
                                    <td><?= ($short_name) ?></td>
                                    <td class="text-center">
                                        <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="manage_products.php?delete_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted py-4'>No products found in stock.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>