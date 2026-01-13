<?php
// track.php - Simple tracking endpoint for updating user status
header('Content-Type: application/json');

// Only handle POST requests (from JavaScript)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $user_id = $input['user_id'] ?? '';
    
    if (empty($user_id)) {
        echo json_encode(['success' => false, 'error' => 'No user_id provided']);
        exit;
    }
    
    $db_file = 'data/users.db';
    if (!file_exists($db_file)) {
        echo json_encode(['success' => false, 'error' => 'Database not found']);
        exit;
    }
    
    $users = json_decode(file_get_contents($db_file), true) ?: [];
    
    if (isset($users[$user_id])) {
        // Update last seen time
        $users[$user_id]['last_seen'] = time();
        
        // Update status if provided
        if (isset($input['status'])) {
            $users[$user_id]['status'] = $input['status'];
        }
        
        // Update redirect_to if provided
        if (isset($input['redirect_to'])) {
            $users[$user_id]['redirect_to'] = $input['redirect_to'];
        }
        
        // Add to history if it's a new status
        if (isset($input['status']) || isset($input['redirect_to'])) {
            $users[$user_id]['history'][] = [
                'time' => time(),
                'action' => 'status_update',
                'status' => $input['status'] ?? '',
                'page' => $input['redirect_to'] ?? ''
            ];
        }
        
        // Save to database
        file_put_contents($db_file, json_encode($users, JSON_PRETTY_PRINT));
        
        echo json_encode(['success' => true, 'message' => 'User updated']);
    } else {
        echo json_encode(['success' => false, 'error' => 'User not found']);
    }
} else {
    // If accessed directly with GET, show minimal info or redirect
    $user_id = $_GET['user_id'] ?? '';
    if (!empty($user_id)) {
        $db_file = 'data/users.db';
        if (file_exists($db_file)) {
            $users = json_decode(file_get_contents($db_file), true) ?: [];
            if (isset($users[$user_id])) {
                // Just show JSON data
                header('Content-Type: application/json');
                echo json_encode($users[$user_id]);
                exit;
            }
        }
    }
    
    // Default response
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
