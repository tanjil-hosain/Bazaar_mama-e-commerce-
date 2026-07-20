<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';
$user_id = $_SESSION['user_id'];
$error = "";

$cart_check = mysqli_query($db, "SELECT cart.*, products.price FROM cart 
                                 JOIN products ON cart.product_id = products.id 
                                 WHERE cart.user_id = $user_id");

if (mysqli_num_rows($cart_check) == 0) {
    header("Location: cart.php");
    exit();
}


$grand_total = 0;
$cart_items = [];
while ($row = mysqli_fetch_assoc($cart_check)) {
    $grand_total += ($row['price'] * $row['quantity']);
    $cart_items[] = $row;
}
if (isset($_POST['place_order'])) {
    $customer_phone = ( $_POST['phone']);
    $delivery_address = ( $_POST['address']);
    $payment_method = ( $_POST['payment_method']);

  
    $customer_name = ( $_SESSION['user_name'] ?? 'Unknown Customer');
    $customer_email = ( $_SESSION['user_email'] ?? '');

    if (empty($customer_phone) || empty($delivery_address)) {
        $error = "Please fill up your phone number and shipping address, Mama!";
    } else {
   
        $order_query = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, delivery_address, total_amount, payment_method, order_status) 
                        VALUES ($user_id, '$customer_name', '$customer_email', '$customer_phone', '$delivery_address', $grand_total, '$payment_method', 'Pending')";

        if (mysqli_query($db, $order_query)) {
            $order_id = mysqli_insert_id($db); 

            foreach ($cart_items as $item) {
                $p_id = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['price'];

                mysqli_query($db, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                   VALUES ($order_id, $p_id, $qty, $price)");
            }

       
            mysqli_query($db, "DELETE FROM cart WHERE user_id = $user_id");

       
            header("Location: order_success.php?order_id=" . $order_id);
            exit();
        } else {
           
            $error = "Database Error: " . mysqli_error($db);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            background: #ffffff;
        }
    </style>
</head>

<body class="py-5">
    <div class="container" style="max-width: 850px;">
        <div class="mb-4">
            <a href="cart.php" class="btn btn-outline-dark fw-bold border-0"><i class="fa-solid fa-arrow-left me-2"></i> Back to Cart</a>
        </div>

        <div class="row g-4">
            
            <div class="col-md-7">
                <div class="card card-custom shadow-sm p-4">
                    <h4 class="fw-bold text-dark mb-4"><i class="fa-solid fa-truck text-primary me-2"></i>Shipping Details</h4>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 small"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="checkout.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 017XXXXXXXX" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Full Shipping Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="House, Road, Area (e.g. Mirpur, Dhaka)" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="COD">Cash on Delivery (COD)</option>
                                <option value="Bkash">bKash</option>
                                <option value="Nagad">Nagad</option>
                            </select>
                        </div>
                        <button type="submit" name="place_order" class="btn btn-primary w-100 fw-bold py-2.5" style="background: #0984e3; border:none; border-radius: 10px;">
                            <i class="fa-solid fa-circle-check me-2"></i> Confirm Order
                        </button>
                    </form>
                </div>
            </div>

            <!-- order-->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4 rounded-4 text-white" style="background: #1e293b; border-radius: 16px;">
                    <h5 class="fw-bold mb-3 text-info">Order Summary</h5>
                    <hr class="border-secondary">

                    <?php
                    $summary_res = mysqli_query($db, "SELECT cart.quantity, products.name, products.price FROM cart 
                                                      JOIN products ON cart.product_id = products.id 
                                                      WHERE cart.user_id = $user_id");
                    while ($item = mysqli_fetch_assoc($summary_res)):
                    ?>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-white-50"><?= ($item['name']) ?> (x<?= $item['quantity'] ?>)</span>
                            <span class="fw-bold">৳<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                        </div>
                    <?php endwhile; ?>

                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 text-info fw-semibold">Total Payable:</span>
                        <span class="fs-4 fw-bold text-success">৳<?= number_format($grand_total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>