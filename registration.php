<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';

$msg = ""; 
$type = "";


if (isset($_SESSION['flash_msg'])) {
    $msg = $_SESSION['flash_msg'];
    $type = $_SESSION['flash_type'];
    
    
    unset($_SESSION['flash_msg']);
    unset($_SESSION['flash_type']);
}

if (isset($_POST['registration'])) {
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = trim($_POST['phone']);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'user')");
        if($stmt->execute([$name, $email, $password, $phone])) {
            

            $_SESSION['flash_msg'] = "🎉 Registration Successful, Mama! Go to Login.";
            $_SESSION['flash_type'] = "success";

            header("Location:registration.php");
            exit();
        }
    } catch (\PDOException $e) {

        if ($e->getCode() == 23000) {
            $msg = "❌ Email Already Registered!";
        } else {
            $msg = "❌ Something went wrong. Please try again.";
        }
        $type = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BazaarMama - Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f3f9ff 0%, #eef1f3 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .register-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: #ffffff;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 12px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(9, 132, 227, 0.2);
            border-color: #0984e3;
        }
        .btn-register {
            background: #0984e3;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            background: #2d3436;
            transform: translateY(-2px);
        }
        .brand-logo {
            font-size: 28px;
            font-weight: 800;
            color: #0984e3;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card register-card p-3">
                    <div class="card-body">
                        
                        <div class="text-center mb-4">
                            <div class="brand-logo mb-1">
                                <i class="fa-solid fa-store me-2"></i>BAZAAR MAMA
                            </div>
                           
                        </div>

                        <form action="" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Rohmat Ali" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark small">Phone Number (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control" placeholder="017XXXXXXXX">
                                </div>
                            </div>

                            <button type="submit"name="registration" class="btn btn-primary btn-register w-100 text-white mb-3 shadow-sm">
                                <i class="fa-solid fa-user-plus me-2"></i> Create Free Account
                            </button>

                            <div class="text-center mt-3">
                                <p class="text-muted small mb-0">Already registered on BazaarMama? 
                                    <a href="login.php" class="text-decoration-none fw-semibold text-primary">Login here</a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php" class="text-white text-decoration-none small opacity-75">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Public Portal
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>