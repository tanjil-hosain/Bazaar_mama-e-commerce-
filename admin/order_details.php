<?php
session_start();
require_once '../config/db.php';
$order_id = intval($_GET['id']);
$order_res = mysqli_query($db, "SELECT * FROM orders WHERE id = $order_id");
$order = mysqli_fetch_assoc($order_res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details #BM-<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 800px;">
        <a href="orders.php" class="btn btn-outline-secondary mb-3">Back to Orders</a>
        <div class="card p-4 shadow-sm">
            <h4 class="mb-4">Order Details #BM-<?= $order_id ?></h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Customer:</strong> <?= ($order['customer_name']) ?></p>
                    <p><strong>Phone:</strong> <?=($order['customer_phone']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Email:</strong> <?= ($order['customer_email']) ?></p>
                    <p><strong>Payment:</strong> <?= ($order['payment_method']) ?></p>
                </div>
            </div>
            <hr>
            <p><strong>Shipping Address:</strong></p>
            <div class="p-3 bg-light border rounded">
                <?= nl2br(htmlspecialchars($order['delivery_address'])) ?>
            </div>
        </div>
    </div>
</body>
</html>