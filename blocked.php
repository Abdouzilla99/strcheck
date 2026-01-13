<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Blocked</title>
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
        
        .blocked-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 40px;
            text-align: center;
        }
        
        .blocked-icon {
            font-size: 64px;
            color: #dc2626;
            margin-bottom: 20px;
        }
        
        .blocked-title {
            color: #dc2626;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .blocked-message {
            font-size: 16px;
            color: #6b7c93;
            line-height: 1.5;
            margin-bottom: 32px;
        }
        
        .contact-support {
            display: block;
            margin-top: 24px;
            font-size: 14px;
            color: #6b7c93;
        }
        
        .contact-support a {
            color: #635bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="blocked-container">
        <div class="blocked-icon">🚫</div>
        <h1 class="blocked-title">Access Blocked</h1>
        <p class="blocked-message">
            Your access has been restricted. Please contact support if you believe this is an error.
        </p>
        <div class="contact-support">
            Contact: <a href="#">support@example.com</a>
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
                    status: 'blocked'
                })
            });
        }
    </script>
</body>
</html>