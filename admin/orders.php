<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';

$msg = "";
$type = "";

if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];
    
    $check_query = mysqli_query($db, "SELECT order_status FROM orders WHERE id = $order_id");
    $current_order = mysqli_fetch_assoc($check_query);
    $old_status = $current_order['order_status'];

    $update_sql = "UPDATE orders SET order_status = '$new_status' WHERE id = $order_id";
    
    if (mysqli_query($db, $update_sql)) {
        if ($new_status == 'Returned' && $old_status !== 'Returned') {
            $items_query = mysqli_query($db, "SELECT product_id, quantity FROM order_items WHERE order_id = $order_id");
            while ($item = mysqli_fetch_assoc($items_query)) {
                $p_id = $item['product_id'];
                $qty = $item['quantity'];
                mysqli_query($db, "UPDATE products SET stock = stock + $qty WHERE id = $p_id");
            }
        }
        $msg = "Order #BM-$order_id status updated to $new_status successfully!";
        $type = "success";
    } else {
        $msg = "Error updating order: " . mysqli_error($db);
        $type = "danger";
    }
}

$orders_result = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .main-content { margin-left: 260px; padding: 40px; }
        .order-card { border: none; border-radius: 15px; background: #fff; }
        @media (max-width: 768px) { .main-content { margin-left: 0 !important; padding: 20px !important; } }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold text-dark">Order Management</h2>
            <p class="text-muted">Control user dispatch, cancellations, and handle system returns.</p>
        </div>

        <?php if(!empty($msg)): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card order-card p-4 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Phone & Address</th>
                            <th>Total Amount</th>
                            <th>Current Status</th>
                            <th>Change Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = mysqli_fetch_assoc($orders_result)): ?>
                        <tr>
                            <td class="fw-bold">#BM-<?= $order['id'] ?></td>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($order['customer_name']) ?></div>
                                <small class="text-muted"><?= $order['payment_method'] ?></small>
                            </td>
                            <td>
                                <div class="fw-bold" style="font-size: 0.85rem;"><?= htmlspecialchars($order['customer_phone']) ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($order['delivery_address']) ?></div>
                            </td>
                            <td class="fw-bold">৳ <?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <?php 
                                $status = $order['order_status'];
                                if ($status == 'Pending') echo '<span class="badge bg-warning text-dark">Pending</span>';
                                elseif ($status == 'Processing') echo '<span class="badge bg-info text-dark">Processing</span>';
                                elseif ($status == 'Delivered') echo '<span class="badge bg-success">Delivered</span>';
                                elseif ($status == 'Cancelled') echo '<span class="badge bg-danger">Cancelled</span>';
                                elseif ($status == 'Returned') echo '<span class="badge bg-danger" style="background-color:#d63031!important">Returned</span>';
                                else echo '<span class="badge bg-secondary">'.$status.'</span>';
                                ?>
                            </td>
                            <td>
                                <form action="" method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="order_status" class="form-select form-select-sm" style="width: 120px;">
                                        <option value="Pending" <?= $status=='Pending'?'selected':'' ?>>Pending</option>
                                        <option value="Processing" <?= $status=='Processing'?'selected':'' ?>>Processing</option>
                                        <option value="Delivered" <?= $status=='Delivered'?'selected':'' ?>>Delivered</option>
                                        <option value="Cancelled" <?= $status=='Cancelled'?'selected':'' ?>>Cancelled</option>
                                        <option value="Returned" <?= $status=='Returned'?'selected':'' ?>>Returned</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary btn-sm fw-bold">Update</button>
                                    <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-dark btn-sm"><i class="fa-solid fa-eye"></i></a>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>