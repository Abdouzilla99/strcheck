<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Payment Error</title>
    <link rel="shortcut icon" href="assets/imgs/favicon.ico">
    <style>
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
        
        .error-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 40px;
            text-align: center;
        }
        
        .error-icon {
            font-size: 64px;
            color: #ef4444;
            margin-bottom: 20px;
        }
        
        .error-title {
            color: #ef4444;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .error-message {
            font-size: 16px;
            color: #6b7c93;
            line-height: 1.5;
            margin-bottom: 32px;
        }
        
        .try-again-btn {
            display: inline-block;
            background: #635bff;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .try-again-btn:hover {
            background: #4f46e5;
        }
        
        .support-link {
            display: block;
            margin-top: 24px;
            font-size: 14px;
            color: #6b7c93;
        }
        
        .support-link a {
            color: #635bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">❌</div>
        <h1 class="error-title">Payment Error</h1>
        <p class="error-message">
            We encountered an error while processing your payment. 
            This could be due to insufficient funds, incorrect card details, 
            or a temporary issue with our payment processor.
        </p>
        <button class="try-again-btn" onclick="window.history.back()">
            Try Again
        </button>
        <div class="support-link">
            Need help? <a href="#">Contact our support team</a>
        </div>
    </div>
    
    <script>
        // Update user status in background
        const urlParams = new URLSearchParams(window.location.search);
        const userID = urlParams.get('user_id');
        
        if (userID) {
            fetch('track.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userID,
                    last_seen: Math.floor(Date.now() / 1000),
                    status: 'redirected',
                    redirect_to: 'error.php'
                })
            });
        }
    </script>
</body>
</html>