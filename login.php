<?php
session_start();
require_once 'config/db.php';
$error = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $sql);


    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "❌ Invalid Email or Password!";
        }
    } else {
        $error = "❌ Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BazaarMama - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container" style="max-width: 400px;">
        <?php if (!empty($error)): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold text-center mb-4">🔐 Login Panel</h3>
                <form action="" method="POST">
                    <div class="mb-3"><label>Email Address</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <button type="submit" name="login" class="btn btn-primary w-100 fw-bold py-2 mb-3"> Login</button>
                    <p class="text-center small">New to BazaarMama? <a href="registration.php">Register Here</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>