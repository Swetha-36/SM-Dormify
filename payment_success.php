<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['payment_id'])) {
    header("Location: rooms.php");
    exit();
}

$payment_id = $_GET['payment_id'];

// Fetch booking details
$stmt = $conn->prepare("SELECT b.*, r.room_name, r.price, p.payment_id 
                        FROM bookings b 
                        JOIN rooms r ON b.room_id = r.id 
                        JOIN payments p ON b.id = p.booking_id 
                        WHERE p.payment_id = ? AND b.user_id = ?");
$stmt->execute([$payment_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-card {
            max-width: 600px;
            margin: 50px auto;
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
        <div class="card-body text-center">
            <div class="success-icon">✓</div>
            <h2 class="text-success mb-4">Payment Successful!</h2>
            <p class="lead">Your booking has been confirmed.</p>
            <hr>
            <?php if ($booking): ?>
            <div class="text-start">
                <p><strong>Booking ID:</strong> #<?php echo $booking['id']; ?></p>
                <p><strong>Room:</strong> <?php echo htmlspecialchars($booking['room_name']); ?></p>
                <p><strong>Amount Paid:</strong> ₹<?php echo number_format($booking['total_amount'], 2); ?></p>
                <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($booking['payment_id']); ?></p>
                <p><strong>Booking Date:</strong> <?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?></p>
            </div>
            <?php endif; ?>
            <hr>
            <a href="rooms.php" class="btn btn-primary">Book Another Room</a>
            <a href="my_bookings.php" class="btn btn-outline-primary">View My Bookings</a>
        </div>
    </div>
</div>
</body>
</html>
