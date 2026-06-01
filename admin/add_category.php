<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}
require_once '../config/db.php';

$msg = "";

// catagory delete
if (isset($_GET['delete_id'])) {
    $delete_id = ($_GET['delete_id']); 
    $delete_sql = "DELETE FROM categories WHERE id = $delete_id";
    
    if (mysqli_query($db, $delete_sql)) {
        $msg = " Category removed successfully!";
    }
}

// catagory add
if (isset($_POST['add_category'])) {
    $name =  $_POST['cat_name'];
    
    $insert_sql = "INSERT INTO categories (name) VALUES ('$name')";
    if (mysqli_query($db, $insert_sql)) {
        $msg = "✅ Category '$name' created successfully!";
    }
}

//  catagory show
$sql = "SELECT * FROM categories ORDER BY id DESC";
$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - BazaarMama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .sidebar { height: 100vh; background: #1e293b; color: white; position: fixed; width: 260px; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 14px 24px; }
        .sidebar a:hover, .sidebar a.active { background: #334155; color: #38bdf8; border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 260px; padding: 40px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="p-4 text-center" style="background:#0f172a;"><h4 class="text-info fw-bold m-0">BazaarMama</h4></div>
        <div class="py-3">
            <a href="dashboard.php"><i class="fa-solid fa-chart-pie me-2"></i> Business Overview</a>
            <div class="px-4 py-2 text-uppercase text-muted small fw-bold">Configurations</div>
            <a href="add_product_type.php"><i class="fa-solid fa-layer-group me-2"></i> Product Types</a>
            <a href="add_category.php" class="active"><i class="fa-solid fa-tags me-2"></i> Categories</a>
            <a href="add_subcategory.php"><i class="fa-solid fa-folder-tree me-2"></i> Sub-Categories</a>
            <a href="add_unit.php"><i class="fa-solid fa-scale-balanced me-2"></i> Product Units</a>
            <div class="px-4 py-2 text-uppercase text-muted small fw-bold">Products</div>
            <a href="add_product.php"><i class="fa-solid fa-plus me-2"></i> Add Product</a>
            <a href="manage_products.php"><i class="fa-solid fa-boxes-stacked me-2"></i> Available Products</a>
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid" style="max-width: 700px; margin-left: 0;">
            <h3 class="fw-bold text-dark mb-4"><i class="fa-solid fa-tags text-primary me-2"></i>Categories Setup</h3>
            
            <?php if(!empty($msg)): ?><div class="alert alert-success border-0 shadow-sm"><?= $msg ?></div><?php endif; ?>
            
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white mb-4">
                <form action="" method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Category Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-tag"></i></span>
                            <input type="text" name="cat_name" class="form-control bg-light" placeholder="e.g. Fashion, Food" required>
                        </div>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm"><i class="fa-solid fa-plus me-2"></i>Add Category</button>
                </form>
            </div>

            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                <h5 class="fw-bold mb-3 text-secondary">Existing Categories</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light">
                            <tr><th>ID</th><th>Category Name</th><th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                           
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($c = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td>#<?= $c['id'] ?></td>
                                        <td class="fw-bold text-dark"><?= ($c['name']) ?></td>
                                        <td class="text-center">
                                            <a href="add_category.php?delete_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center text-muted py-3'>No categories found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>