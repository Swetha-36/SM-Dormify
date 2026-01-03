<?php
session_start();
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'success';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .success-card {
            max-width: 500px;
            margin: 100px auto;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card success-card">
        <div class="card-body">
            <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
            <h2 class="text-success">Payment Submitted!</h2>
            <?php if ($status == 'pending'): ?>
                <p class="lead">Your booking request has been received.</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Your payment will be verified within 24 hours. You'll receive a confirmation email once verified.
                </div>
            <?php else: ?>
                <p class="lead">Your booking has been confirmed.</p>
            <?php endif; ?>
            <hr>
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
            <p><strong>Amount:</strong> ₹<?php echo number_format($amount, 2); ?></p>
            <hr>
            <a href="rooms1.php" class="btn btn-primary mt-3">Back to Rooms</a>
            <a href="index.php" class="btn btn-outline-secondary mt-3">Go to Home</a>
        </div>
    </div>
</div>
</body>
</html>
