<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    :root { 
        --sidebar-bg: #1e293b; 
        --sidebar-hover: #334155; 
        --primary-color: #0f172a; 
    }
    .sidebar { 
        height: 100vh; 
        background: var(--sidebar-bg); 
        color: white; 
        position: fixed; 
        width: 260px; 
        box-shadow: 4px 0 10px rgba(0,0,0,0.1); 
        display: flex;
        flex-direction: column;
    }
    .sidebar .brand { 
        padding: 20px; 
        background: var(--primary-color); 
        font-size: 1.25rem; 
        flex-shrink: 0;
    }
    
    .sidebar-menu-wrapper {
        flex-grow: 1;
        overflow-y: auto; 
        padding-bottom: 100px; 
    }
    .sidebar a { 
        color: #cbd5e1; 
        text-decoration: none; 
        display: block; 
        padding: 14px 24px; 
        font-weight: 500; 
        transition: all 0.2s; 
    }
    .sidebar a:hover, .sidebar a.active { 
        background: var(--sidebar-hover); 
        color: #38bdf8; 
        border-left: 4px solid #38bdf8; 
    }
    .sidebar-footer-buttons { 
        position: absolute; 
        bottom: 15px; 
        width: 100%; 
        padding: 0 20px; 
        background: var(--sidebar-bg);
    }
  
    .sidebar-menu-wrapper::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-menu-wrapper::-webkit-scrollbar-track {
        background: var(--sidebar-bg);
    }
    .sidebar-menu-wrapper::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 10px;
    }
    .sidebar-menu-wrapper::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>

<div class="sidebar">
    <div class="brand text-center">
        <h4 class="text-info fw-bold m-0"><i class="fa-solid fa-shop me-2"></i>BazaarMama</h4>
    </div>

    <div class="sidebar-menu-wrapper">
        <div class="py-3">
            <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie me-2"></i> Business Overview
            </a>
            
            <div class="px-4 py-2 text-uppercase text-white small fw-bold" style="font-size: 11px; letter-spacing: 1px; opacity: 0.6;">Configurations</div>
            <a href="add_product_type.php" class="<?= $current_page == 'add_product_type.php' ? 'active' : '' ?>"><i class="fa-solid fa-layer-group me-2"></i> Product Types</a>
            <a href="add_category.php" class="<?= $current_page == 'add_category.php' ? 'active' : '' ?>"><i class="fa-solid fa-tags me-2"></i> Categories</a>
            <a href="add_subcategory.php" class="<?= $current_page == 'add_subcategory.php' ? 'active' : '' ?>"><i class="fa-solid fa-folder-tree me-2"></i> Sub-Categories</a>
            <a href="add_unit.php" class="<?= $current_page == 'add_unit.php' ? 'active' : '' ?>"><i class="fa-solid fa-scale-balanced me-2"></i> Product Units</a>
            
            <div class="px-4 py-2 text-uppercase text-white small fw-bold" style="font-size: 11px; letter-spacing: 1px; opacity: 0.6;">Products & Orders</div>
            <a href="add_product.php" class="<?= $current_page == 'add_product.php' ? 'active' : '' ?>"><i class="fa-solid fa-plus me-2"></i> Add Product</a>
            <a href="manage_products.php" class="<?= $current_page == 'manage_products.php' ? 'active' : '' ?>"><i class="fa-solid fa-boxes-stacked me-2"></i> Available Products</a>
            
            <a href="orders.php" class="<?= $current_page == 'orders.php' || $current_page == 'order_details.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-truck-ramp-box me-2"></i> Order Management
            </a>
        </div>
    </div>

    <div class="sidebar-footer-buttons d-grid gap-2">
        <a href="../index.php" target="_blank" class="btn btn-sm btn-info text-dark fw-bold py-2">
            <i class="fa-solid fa-globe me-2"></i> Visit Site
        </a>
    </div>
</div>