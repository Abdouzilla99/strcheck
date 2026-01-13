<?php
session_start();
$admin_password = 'admin123';

if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $admin_password) {
        $_SESSION['admin_loggedin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Incorrect Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background: #1a202c;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-container h2 {
            margin-bottom: 30px;
            color: #00f0ff;
            font-size: 24px;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 240, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 16px;
        }
        
        input[type="password"]:focus {
            outline: none;
            border-color: #00f0ff;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: #00f0ff;
            color: black;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        button:hover {
            background: #00c4d4;
            transform: translateY(-2px);
        }
        
        .error {
            color: #ff2a6d;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Payment Control Panel</h2>
        <form method="POST" action="">
            <input type="password" name="password" placeholder="Enter admin password" required>
            <button type="submit">Login</button>
            <?php if(isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>