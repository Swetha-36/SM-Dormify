<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .failure-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 500px;
            text-align: center;
        }
        
        .failure-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .btn-retry {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .reason {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="failure-container">
        <div class="failure-icon">✗</div>
        <h1>Payment Failed</h1>
        <p>Unfortunately, your payment could not be processed.</p>
        
        <?php if (isset($_GET['reason'])): ?>
        <div class="reason">
            <strong>Reason:</strong> <?php echo htmlspecialchars($_GET['reason']); ?>
        </div>
        <?php endif; ?>
        
        <p>Please try again or contact support if the problem persists.</p>
        
        <a href="booking.html" class="btn-retry">Try Again</a>
    </div>
</body>
</html>
