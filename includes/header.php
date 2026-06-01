<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Modern E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .navbar-brand-custom {
            font-weight: 800;
            color: #0984e3;
            letter-spacing: 0.5px;
        }
        .nav-link-custom {
            font-weight: 600;
            color: #2d3436 !important;
            transition: color 0.2s ease;
        }
        .nav-link-custom:hover {
            color: #0984e3 !important;
        }
        .hero-banner {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            border-radius: 24px;
            padding: 60px;
            color: white;
            margin-bottom: 40px;
            box-shadow: 0 12px 30px rgba(9, 132, 227, 0.2);
        }
        .product-card {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(0,0,0,0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        }
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            color: #2d3436;
            font-weight: 700;
            border-radius: 30px;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-modern-primary {
            background: #0984e3;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.2s ease;
        }
        .btn-modern-primary:hover {
            background: #2d3436;
            color: white;
        }
        .footer-custom {
            background-color: #96ddfef4;
            color: #b2bec3;
            padding: 60px 0 30px 0;
            margin-top: 80px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom fs-3" href="index.php">
                <i class="fa-solid fa-bag-shopping me-2 text-primary"></i>BAZAAR MAMA
            </a>
            <button class="navbar-collapse-toggle d-lg-none border-0 bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <i class="fa-solid fa-bars fs-4 text-dark"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Shop All</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Categories</a></li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <?php if(isset($_SESSION['user_name'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark border-0 fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fa-regular fa-user me-2"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-2 rounded-3">
                                <?php if($_SESSION['user_role'] == 'admin'): ?>
                                    <li><a class="dropdown-item py-2 fw-semibold" href="admin/dashboard.php"><i class="fa-solid fa-gauge me-2 text-primary"></i>Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-semibold" href="admin/dashboard.php?action=logout"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-dark px-4 border-0 fw-semibold">Sign In</a>
                        <a href="registration.php" class="btn btn-modern-primary px-4 shadow-sm">Join Free</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>