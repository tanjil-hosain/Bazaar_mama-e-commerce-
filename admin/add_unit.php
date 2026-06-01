<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}
require_once '../config/db.php';

$msg = "";


if (isset($_GET['delete_id'])) {
    $delete_id = ($_GET['delete_id']); 
    $delete_sql = "DELETE FROM product_units WHERE id = $delete_id";
    
    if (mysqli_query($db, $delete_sql)) {
        $msg = " Unit removed successfully!";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_unit'])) {
    $name =  $_POST['unit_name'];
    $short =  $_POST['unit_short'];
    
    $insert_sql = "INSERT INTO product_units (unit_name, short_name) VALUES ('$name', '$short')";
    if (mysqli_query($db, $insert_sql)) {
        $msg = "✅ Unit '$name' created successfully!";
    }
}


$sql = "SELECT * FROM product_units ORDER BY id DESC";
$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Product Units - BazaarMama</title>
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
            <a href="add_category.php"><i class="fa-solid fa-tags me-2"></i> Categories</a>
            <a href="add_subcategory.php"><i class="fa-solid fa-folder-tree me-2"></i> Sub-Categories</a>
            <a href="add_unit.php" class="active"><i class="fa-solid fa-scale-balanced me-2"></i> Product Units</a>
            <div class="px-4 py-2 text-uppercase text-muted small fw-bold">Products</div>
            <a href="add_product.php"><i class="fa-solid fa-plus me-2"></i> Add Product</a>
            <a href="manage_products.php"><i class="fa-solid fa-boxes-stacked me-2"></i> Available Products</a>
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid" style="max-width: 700px; margin-left: 0;">
            <h3 class="fw-bold text-dark mb-4"><i class="fa-solid fa-scale-balanced text-warning me-2"></i>Product Units Setup</h3>
            
            <?php if(!empty($msg)): ?><div class="alert alert-success border-0 shadow-sm"><?= $msg ?></div><?php endif; ?>
            
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white mb-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Unit Full Name</label>
                        <input type="text" name="unit_name" class="form-control bg-light" placeholder="e.g. Kilogram, Piece" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Short Name</label>
                        <input type="text" name="unit_short" class="form-control bg-light" placeholder="e.g. KG, Pcs" required>
                    </div>
                    <button type="submit" name="add_unit" class="btn btn-warning w-100 py-2.5 fw-bold shadow-sm text-dark"><i class="fa-solid fa-plus me-2"></i>Add Unit</button>
                </form>
            </div>

            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                <h5 class="fw-bold mb-3 text-secondary">Existing Product Units</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light">
                            <tr><th>ID</th><th>Unit Name</th><th>Short Code</th><th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($u = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td>#<?= $u['id'] ?></td>
                                        <td class="fw-bold text-dark"><?= ($u['unit_name']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= ($u['short_name']) ?></span></td>
                                        <td class="text-center">
                                            <a href="add_unit.php?delete_id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this unit?')"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-muted py-3'>No product units found.</td></tr>";
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