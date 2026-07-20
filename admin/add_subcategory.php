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
    $delete_sql = "DELETE FROM sub_categories WHERE id = $delete_id";
    if (mysqli_query($db, $delete_sql)) {
        $msg = " Sub-Category removed successfully!";
    }
}

// add
if (isset($_POST['add_subcategory'])) {
    $name = $_POST['subcat_name'];
    $type_id = $_POST['product_type_id'];
    
    $insert_sql = "INSERT INTO sub_categories (name, product_type_id) VALUES ('$name', $type_id)";
    if (mysqli_query($db, $insert_sql)) {
        $msg = " Sub-Category '$name' created successfully!";
    }
}


$types_result = mysqli_query($db, "SELECT * FROM product_types");

$subcat_sql = "SELECT * FROM sub_categories ORDER BY id DESC";
$subcat_result = mysqli_query($db, $subcat_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Sub-Categories - BazaarMama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .main-content { margin-left: 260px; padding: 40px; }
    </style>
</head>
<body>
     <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="container-fluid" style="max-width: 700px; margin-left: 0;">
            <h3 class="fw-bold text-dark mb-4"><i class="fa-solid fa-folder-tree text-success me-2"></i>Sub-Categories Setup</h3>
            
            <?php if(!empty($msg)): ?><div class="alert alert-success border-0 shadow-sm"><?= $msg ?></div><?php endif; ?>
            
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white mb-4">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Select Product Type</label>
                        <select name="product_type_id" class="form-select bg-light" required>
                            <option value="">-- Choose Product Type --</option>
                            <?php 
                           
                            while($t = mysqli_fetch_assoc($types_result)) {
                                echo "<option value='".$t['id']."'>".($t['type_name'])."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Sub-Category Name</label>
                        <input type="text" name="subcat_name" class="form-control bg-light" placeholder="e.g. T-Shirt, Gadgets" required>
                    </div>
                    <button type="submit" name="add_subcategory" class="btn btn-success w-100 py-2.5 fw-bold shadow-sm text-white"><i class="fa-solid fa-plus me-2"></i>Add Sub-Category</button>
                </form>
            </div>

            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                <h5 class="fw-bold mb-3 text-secondary">Existing Sub-Categories</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light">
                            <tr><th>ID</th><th>Sub-Category</th><th>Linked Product Type</th><th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($subcat_result && mysqli_num_rows($subcat_result) > 0) {
                                while ($sc = mysqli_fetch_assoc($subcat_result)) {

                                    $pt_id = $sc['product_type_id'];
                                    $pt_name = "N/A"; 
                                    
                                    if(!empty($pt_id)) {
                                        $pt_res = mysqli_query($db, "SELECT type_name FROM product_types WHERE id = $pt_id");
                                        if($pt_row = mysqli_fetch_assoc($pt_res)) {
                                            $pt_name = $pt_row['type_name'];
                                        }
                                    }
                            ?>
                                    <tr>
                                        <td>#<?= $sc['id'] ?></td>
                                        <td class="fw-bold text-dark"><?= ($sc['name']) ?></td>
                                        <td><span class="badge bg-info-subtle text-info px-2 py-1"><?= ($pt_name) ?></span></td>
                                        <td class="text-center">
                                            <a href="add_subcategory.php?delete_id=<?= $sc['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this sub-category?')"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-muted py-3'>No sub-categories found.</td></tr>";
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