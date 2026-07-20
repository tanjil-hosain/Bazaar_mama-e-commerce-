<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$user_id = $_SESSION['user_id'];

$order_res = mysqli_query($db, "SELECT orders.*, users.name as u_name FROM orders 
                                 JOIN users ON orders.user_id = users.id 
                                 WHERE orders.id = $order_id AND orders.user_id = $user_id");
$order = mysqli_fetch_assoc($order_res);

if (!$order) {
    echo "Order not found, Mama!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #BM-<?= $order['id'] ?> - BazaarMama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; color: #334155; }
        .invoice-card { border: none; border-radius: 24px; background: #ffffff; overflow: hidden; }
        .invoice-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 40px; }
        .brand-logo { font-size: 28px; font-weight: 800; letter-spacing: -0.5px; }
        .brand-logo span { color: #38bdf8; }
        .status-badge { padding: 6px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; text-uppercase: true; }
        .info-label { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 15px; font-weight: 600; color: #334155; }
        .payment-method-badge { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 4px 12px; border-radius: 6px; font-weight: 600; font-size: 14px; }
        .payment-bkash { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
        .payment-nagad { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
        @media print {
            body { background: #ffffff; }
            .invoice-card { shadow: none; border: none; }
            .btn-print-group { display: none; }
        }
    </style>
</head>
<body class="py-5">

    <div class="container" style="max-width: 750px;">
        <div class="card invoice-card shadow-lg mb-4">
            
            <div class="invoice-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="brand-logo mb-1"><i class="fa-solid fa-bag-shopping text-info me-2"></i>Bazaar<span>Mama</span></div>
                    <p class="text-white-50 small m-0">Premium Dynamic Shopping Hub</p>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold text-uppercase m-0 tracking-wider" style="letter-spacing: 1px;">Invoice</h3>
                    <span class="text-info fw-semibold">#BM-<?= $order['id'] ?></span>
                </div>
            </div>

          
            <div class="card-body p-5">
                
                
                <div class="alert alert-success border-0 d-flex align-items-center p-3 mb-5" style="background: #f0fdf4; border-radius: 12px;">
                    <i class="fa-solid fa-circle-check text-success fs-3 me-3"></i>
                    <div>
                        <h6 class="alert-heading fw-bold text-success mb-0">Order Confirmed Successfully!</h6>
                        <small class="text-success opacity-75">Thank you for shopping, Mama! Your order has been registered into our queue.</small>
                    </div>
                </div>

                
                <div class="row g-4 mb-5">
                    <div class="col-6 col-sm-3">
                        <div class="info-label">Billed To</div>
                        <div class="info-value"><?= ($order['u_name']) ?></div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="info-label">Date Issued</div>
                        <div class="info-value"><?= date('d M, Y', strtotime($order['order_date'])) ?></div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="info-label">Payment Mode</div>
                        <div>
                            <?php 
                            $p_method = $order['payment_method'];
                            if(strtolower($p_method) == 'bkash'): ?>
                                <span class="payment-method-badge payment-bkash"><i class="fa-solid fa-wallet me-1"></i> bKash</span>
                            <?php elseif(strtolower($p_method) == 'nagad'): ?>
                                <span class="payment-method-badge payment-nagad"><i class="fa-solid fa-wallet me-1"></i> Nagad</span>
                            <?php else: ?>
                                <span class="payment-method-badge"><i class="fa-solid fa-money-bill-wave me-1"></i> COD</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="info-label">Order Status</div>
                        <div>
                            <span class="badge bg-warning text-dark fw-bold status-badge shadow-sm"><?= $order['order_status'] ?></span>
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-muted opacity-25">

              
                <div class="row mb-5">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-phone text-muted me-2"></i>Contact Information</h6>
                        <p class="text-secondary small m-0 fw-semibold"><?=($order['customer_phone']) ?></p>
                        <p class="text-muted small m-0"><?= ($order['customer_email']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-location-dot text-muted me-2"></i>Shipping Destination</h6>
                        <p class="text-secondary small m-0 lh-base"><?= nl2br(($order['delivery_address'])) ?></p>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr class="border-bottom text-muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase;">
                                <th style="width: 55%;">Product Specifications</th>
                                <th class="text-center" style="width: 15%;">Qty</th>
                                <th class="text-end" style="width: 15%;">Unit Price</th>
                                <th class="text-end" style="width: 15%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $items_res = mysqli_query($db, "SELECT order_items.*, products.name FROM order_items 
                                                            JOIN products ON order_items.product_id = products.id 
                                                            WHERE order_items.order_id = $order_id");
                            while($item = mysqli_fetch_assoc($items_res)):
                                $subtotal = $item['price'] * $item['quantity'];
                            ?>
                                <tr class="border-bottom-dashed" style="border-bottom: 1px dashed #e2e8f0;">
                                    <td class="fw-bold text-slate py-3"><?= ($item['name']) ?></td>
                                    <td class="text-center fw-semibold text-secondary"><?= $item['quantity'] ?></td>
                                    <td class="text-end text-muted">৳<?= number_format($item['price'], 2) ?></td>
                                    <td class="text-end fw-bold text-dark">৳<?= number_format($subtotal, 2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                            
                         
                            <tr style="background: #f8fafc;">
                                <td colspan="2"></td>
                                <td class="text-end fw-bold py-3 text-secondary" style="font-size: 15px;">Grand Total:</td>
                                <td class="text-end fw-extrabold text-primary py-3" style="font-size: 22px; font-weight: 800;">৳<?= number_format($order['total_amount'], 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

           
                <div class="text-center mt-5">
                    <p class="text-muted small mb-0">If you have any inquiries regarding this transactional invoice, please contact support.</p>
                    <p class="fw-bold text-primary small">Thank you for trusting BazaarMama!</p>
                </div>

            </div>
        </div>

        <div class="d-flex gap-3 justify-content-center btn-print-group">
            <a href="../index.php" class="btn btn-dark fw-bold px-4 py-2.5 rounded-3 border-0 shadow-sm" style="background: #1e293b;">
                <i class="fa-solid fa-house-user me-2"></i>Return Home
            </a>
            <button onclick="window.print()" class="btn btn-primary fw-bold px-4 py-2.5 rounded-3 border-0 shadow-sm" style="background: #0284c7;">
                <i class="fa-solid fa-print me-2"></i>Print/Save Invoice
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>