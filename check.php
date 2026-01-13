<?php
header('Content-Type: application/json');

$db_file = 'data/users.db';

if (!file_exists($db_file)) {
    echo json_encode(['shouldRedirect' => false]);
    exit;
}

$users = json_decode(file_get_contents($db_file), true) ?: [];
$user_id = $_GET['user_id'] ?? '';

if (!$user_id) {
    echo json_encode(['shouldRedirect' => false, 'error' => 'No user_id provided']);
    exit;
}

// Update last_seen time
if (isset($users[$user_id])) {
    $users[$user_id]['last_seen'] = time();
    $users[$user_id]['current_page'] = $_GET['page'] ?? $users[$user_id]['current_page'] ?? 'loading.php';
    file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
}

// Check if user should be redirected
if (isset($users[$user_id])) {
    $user = $users[$user_id];
    
    if ($user['status'] === 'redirected' && !empty($user['redirect_to'])) {
        echo json_encode([
            'shouldRedirect' => true,
            'redirectTo' => $user['redirect_to'],
            'status' => 'redirected'
        ]);
        exit;
    }
    
    if ($user['status'] === 'blocked') {
        echo json_encode([
            'shouldRedirect' => true,
            'redirectTo' => 'blocked.php',
            'status' => 'blocked'
        ]);
        exit;
    }
}

echo json_encode([
    'shouldRedirect' => false,
    'status' => $users[$user_id]['status'] ?? 'waiting',
    'last_seen' => $users[$user_id]['last_seen'] ?? time()
]);
?>