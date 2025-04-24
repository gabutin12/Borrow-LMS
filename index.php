<?php
session_start();
require_once 'db_connection.php';
require_once 'check_remember.php';

// Check if user is already logged in
if (isset($_SESSION['user_id']) || checkRememberMe($conn)) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Login</title>
    <link rel="icon" href="images/top_logo.ico" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #eceff1 100%);
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: left;
        }

        .login-box h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-control {
            background: #f7f9fc;
            border: 1px solid #e3e8ef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            background: #fff;
        }

        .btn-primary {
            background: #4a90e2;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: #357abd;
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1);
        }

        .separator {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .alert {
            border-radius: 8px;
            margin-top: 20px;
        }

        .blink_text {
            color: #dc3545;
            font-size: 14px;
            font-weight: 500;
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid login-container">
        <div class="login-box">
            <h2 class="text-center mb-4">Library Management Login</h2>
            <div class="text-center mb-4">
                <img src="images/top_logo.ico" alt="Library Logo" class="img-fluid" style="max-width: 60px;">
            </div>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <form id="loginForm" action="login_process.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="separator text-center">
                    <div>
                        <h4><i class="fa fa-university"></i> College of Nursing</h4>
                        <h6>© <?php echo date('Y'); ?> Library Management System</h6>
                    </div>
                </div>
                <?php if (isset($error_msg)): ?>
                    <div class="alert alert-danger">
                        <h3 class="blink_text"><?php echo htmlspecialchars($error_msg); ?></h3>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>