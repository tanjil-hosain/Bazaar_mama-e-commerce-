<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $_SESSION = array(); 
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy(); 
    header("Location: ../login.php");
    exit();
}

$sales_query = "SELECT SUM(total_amount) AS total_sales FROM orders WHERE order_status = 'Delivered'";
$sales_result = mysqli_query($db, $sales_query);
$sales_data = mysqli_fetch_assoc($sales_result);
$total_sales = $sales_data['total_sales'] ? $sales_data['total_sales'] : 0;


$pending_query = "SELECT COUNT(id) AS total_pending FROM orders WHERE order_status = 'Pending'";
$pending_result = mysqli_query($db, $pending_query);
$pending_data = mysqli_fetch_assoc($pending_result);
$total_pending = $pending_data['total_pending'] ? $pending_data['total_pending'] : 0;


$qty_query = "SELECT SUM(quantity) AS total_qty FROM order_items 
              JOIN orders ON order_items.order_id = orders.id 
              WHERE orders.order_status = 'Delivered'";
$qty_result = mysqli_query($db, $qty_query);
$qty_data = mysqli_fetch_assoc($qty_result);
$total_products_sold = $qty_data['total_qty'] ? $qty_data['total_qty'] : 0;

//net profit 
$profit_query = "SELECT SUM((oi.price - p.buying_price) * oi.quantity) AS net_profit 
                 FROM order_items oi
                 JOIN products p ON oi.product_id = p.id
                 JOIN orders o ON oi.order_id = o.id
                 WHERE o.order_status = 'Delivered'";
$profit_result = mysqli_query($db, $profit_query);
$net_profit = 0;
if ($profit_result) {
    $profit_data = mysqli_fetch_assoc($profit_result);
    $net_profit = $profit_data['net_profit'] ? $profit_data['net_profit'] : 0;
}

//return
$return_query = "SELECT SUM(total_amount) AS total_returned FROM orders WHERE order_status = 'Returned'";
$return_result = mysqli_query($db, $return_query);
$return_data = mysqli_fetch_assoc($return_result);
$total_returned = $return_data['total_returned'] ? $return_data['total_returned'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Business Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', Roboto, sans-serif; }
        .main-content { margin-left: 260px; padding: 40px; }
        .stat-card { border: none; border-radius: 15px; transition: all 0.2s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
        .table-card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
        
        @media (max-width: 768px) {
            .main-content { margin-left: 0 !important; padding: 20px !important; }
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold text-dark m-0">Business Analytics</h2>
                <p class="text-muted m-0">Welcome back, Mama! Live track of your system sales & updates.</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <div class="bg-white p-2 rounded shadow-sm text-muted fw-medium small">
                    <i class="fa-regular fa-calendar me-2"></i><?php echo date('F d, Y'); ?>
                </div>
                <a href="dashboard.php?action=logout" class="btn btn-outline-danger btn-sm fw-bold px-3 py-2 shadow-sm" onclick="return confirm('Are you sure you want to logout, Mama?')">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                </a>
            </div>
        </div>
        
        <div class="row g-4 mt-2">
            
            <div class="col">
                <div class="card stat-card bg-white p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small" style="font-size: 11px;">Total Sales</h6>
                            <h3 class="fw-bold text-success m-0">৳ <?= number_format($total_sales, 2) ?></h3>
                        </div>
                        <div class="bg-success-subtle p-3 rounded-circle text-success fs-4"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <div class="mt-2 small text-success fw-medium"><i class="fa-solid fa-arrow-trend-up me-1"></i>Delivered orders</div>
                </div>
            </div>
            
            <div class="col">
                <div class="card stat-card bg-white p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small" style="font-size: 11px;">Net Profit</h6>
                            <h3 class="fw-bold text-primary m-0">৳ <?= number_format($net_profit, 2) ?></h3>
                        </div>
                        <div class="bg-primary-subtle p-3 rounded-circle text-primary fs-4"><i class="fa-solid fa-chart-line"></i></div>
                    </div>
                    <div class="mt-2 small text-primary fw-medium"><i class="fa-solid fa-arrow-trend-up me-1"></i>Margin tracked</div>
                </div>
            </div>
            <!-- return card -->
            <div class="col">
                <div class="card stat-card bg-white p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small" style="font-size: 11px;">Total Returned</h6>
                            <h3 class="fw-bold text-danger m-0">৳ <?= number_format($total_returned, 2) ?></h3>
                        </div>
                        <div class="bg-danger-subtle p-3 rounded-circle text-danger fs-4"><i class="fa-solid fa-arrow-rotate-left"></i></div>
                    </div>
                    <div class="mt-2 small text-danger fw-medium"><i class="fa-solid fa-triangle-exclamation me-1"></i>Returned items</div>
                </div>
            </div>
            
            <div class="col">
                <div class="card stat-card bg-white p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small" style="font-size: 11px;">Pending Orders</h6>
                            <h3 class="fw-bold text-warning m-0"><?= $total_pending ?> New</h3>
                        </div>
                        <div class="bg-warning-subtle p-3 rounded-circle text-warning fs-4"><i class="fa-solid fa-truck-ramp-box"></i></div>
                    </div>
                    <div class="mt-2 small text-danger fw-medium"><i class="fa-solid fa-clock me-1"></i>Needs dispatch</div>
                </div>
            </div>
            
            <div class="col">
                <div class="card stat-card bg-white p-4 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small" style="font-size: 11px;">Products Sold</h6>
                            <h3 class="fw-bold text-info m-0"><?= $total_products_sold ?> Pcs</h3>
                        </div>
                        <div class="bg-info-subtle p-3 rounded-circle text-info fs-4"><i class="fa-solid fa-basket-shopping"></i></div>
                    </div>
                    <div class="mt-2 small text-info fw-medium"><i class="fa-solid fa-bolt me-1"></i>Volume counter</div>
                </div>
            </div>
        </div>

        <div class="card table-card mt-5 p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-list-check me-2 text-secondary"></i>Recent Transactions</h5>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 small fw-bold">Live Logs (Max 5)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Payment Method</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_orders = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC LIMIT 5");
                        if ($recent_orders && mysqli_num_rows($recent_orders) > 0):
                            while ($order = mysqli_fetch_assoc($recent_orders)):
                        ?>
                            <tr>
                                <td class="fw-bold text-primary">#BM-<?= $order['id'] ?></td>
                                <td class="fw-semibold text-secondary"><?= ($order['customer_name']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-medium"><?= $order['payment_method'] ?></span>
                                </td>
                                <td class="fw-bold">৳ <?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <?php 
                                    $status = $order['order_status'];
                                    if ($status == 'Pending') {
                                        echo '<span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold">Pending</span>';
                                    } elseif ($status == 'Delivered') {
                                        echo '<span class="badge bg-success rounded-pill px-3 py-1.5 fw-bold">Delivered</span>';
                                    } elseif ($status == 'Cancelled') {
                                        echo '<span class="badge bg-danger rounded-pill px-3 py-1.5 fw-bold">Cancelled</span>';
                                    } elseif ($status == 'Returned') {
                                        echo '<span class="badge bg-danger rounded-pill px-3 py-1.5 fw-bold" style="background-color: #d63031 !important;">Returned</span>';
                                    } else {
                                        echo '<span class="badge bg-info text-dark rounded-pill px-3 py-1.5 fw-bold">'.$status.'</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No transactions recorded yet, Mama!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>