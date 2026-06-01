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
        :root { --sidebar-bg: #1e293b; --sidebar-hover: #334155; --primary-color: #0f172a; }
        body { background-color: #f8fafc; font-family: 'Segoe UI', Roboto, sans-serif; }
        .sidebar { height: 100vh; background: var(--sidebar-bg); color: white; position: fixed; width: 260px; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar .brand { padding: 20px; background: var(--primary-color); font-size: 1.25rem; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 14px 24px; font-weight: 500; transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: var(--sidebar-hover); color: #38bdf8; border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 260px; padding: 40px; }
        .stat-card { border: none; border-radius: 15px;   }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
        

        .sidebar-footer-buttons { position: absolute; bottom: 15px; width: 100%; padding: 0 20px; }
        .table-card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand text-center">
            <h4 class="text-info fw-bold m-0"><i class="fa-solid fa-shop me-2"></i>BazaarMama</h4>
        </div>
        <div class="py-3">
            <a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie me-2"></i> Business Overview</a>
            
            <div class="px-4 py-2 text-uppercase text-white small fw-bold" style="font-size: 11px; letter-spacing: 1px; opacity: 0.6;">Configurations</div>
            <a href="add_product_type.php"><i class="fa-solid fa-layer-group me-2"></i> Product Types</a>
            <a href="add_category.php"><i class="fa-solid fa-tags me-2"></i> Categories</a>
            <a href="add_subcategory.php"><i class="fa-solid fa-folder-tree me-2"></i> Sub-Categories</a>
            <a href="add_unit.php"><i class="fa-solid fa-scale-balanced me-2"></i> Product Units</a>
            
            <div class="px-4 py-2 text-uppercase text-white small fw-bold" style="font-size: 11px; letter-spacing: 1px; opacity: 0.6;">Products</div>
            <a href="add_product.php"><i class="fa-solid fa-plus me-2"></i> Add Product</a>
            <a href="manage_products.php"><i class="fa-solid fa-boxes-stacked me-2"></i> Available Products</a>
            
            <div class="sidebar-footer-buttons d-grid gap-2">
                <a href="../index.php" target="_blank" class="btn btn-sm btn-info text-dark fw-bold py-2">
                    <i class="fa-solid fa-globe me-2"></i> Visit Site
                </a>
               
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark m-0">Business Analytics</h2>
                <p class="text-muted">Welcome back, Mama! Live track of your system sales & updates.</p>
            </div>
            <div class="bg-white p-2 rounded shadow-sm text-muted fw-medium">
                <i class="fa-regular fa-calendar me-2"></i><?php echo date('F d, Y'); ?>
            </div>
        </div>
        
        <div class="row g-4 mt-2">
            <div class="col-md-3">
                <div class="card stat-card bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small">Total Sales</h6>
                            <h3 class="fw-bold text-success m-0">৳ 1,45,800</h3>
                        </div>
                        <div class="bg-success-subtle p-3 rounded-circle text-success fs-4"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <div class="mt-2 small text-success fw-medium"><i class="fa-solid fa-arrow-trend-up me-1"></i>+12% up from yesterday</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small">Net Profit</h6>
                            <h3 class="fw-bold text-primary m-0">৳ 32,450</h3>
                        </div>
                        <div class="bg-primary-subtle p-3 rounded-circle text-primary fs-4"><i class="fa-solid fa-chart-line"></i></div>
                    </div>
                    <div class="mt-2 small text-primary fw-medium"><i class="fa-solid fa-arrow-trend-up me-1"></i>Stable margin</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small">Pending Orders</h6>
                            <h3 class="fw-bold text-warning m-0">18 New</h3>
                        </div>
                        <div class="bg-warning-subtle p-3 rounded-circle text-warning fs-4"><i class="fa-solid fa-truck-ramp-box"></i></div>
                    </div>
                    <div class="mt-2 small text-danger fw-medium"><i class="fa-solid fa-clock me-1"></i>Requires dispatch attention</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-bold small">Products Sold</h6>
                            <h3 class="fw-bold text-info m-0">540 Pcs</h3>
                        </div>
                        <div class="bg-info-subtle p-3 rounded-circle text-info fs-4"><i class="fa-solid fa-basket-shopping"></i></div>
                    </div>
                    <div class="mt-2 small text-info fw-medium"><i class="fa-solid fa-bolt me-1"></i>Fast moving items</div>
                </div>
            </div>
        </div>

        <div class="card table-card mt-5 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-list-check me-2 text-secondary"></i>Recent Transactions</h5>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Live Logs</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light">
                        <tr><th>Order ID</th><th>Customer Name</th><th>Items</th><th>Total Amount</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td class="fw-bold text-secondary">#BM-9081</td><td>Rakibul Hasan</td><td>Premium T-Shirt (x2)</td><td>৳ 1,200</td><td><span class="badge bg-success rounded-pill px-2.5">Delivered</span></td></tr>
                        <tr><td class="fw-bold text-secondary">#BM-9082</td><td>Anika Tahsin</td><td>Wireless Earbuds (x1)</td><td>৳ 2,450</td><td><span class="badge bg-warning text-dark rounded-pill px-2.5">Pending</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>