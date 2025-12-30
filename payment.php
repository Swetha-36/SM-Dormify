<?php
session_start();
include 'config.php'; // Your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get room details from URL
if (!isset($_GET['room_id']) || !isset($_GET['price'])) {
    header("Location: rooms1.php");
    exit();
}

$room_id = $_GET['room_id'];
$price = $_GET['price'];
$room_name = isset($_GET['room_name']) ? $_GET['room_name'] : 'Room';

// Get user details from session/database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Razorpay API Keys (Get from https://dashboard.razorpay.com/app/website-app-settings/api-keys)
$razorpay_key_id = "rzp_test_YOUR_KEY_ID"; // Replace with your key
$razorpay_secret = "YOUR_SECRET_KEY"; // Replace with your secret
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment - Hostel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .payment-card {
            max-width: 500px;
            margin: 50px auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="rooms.php">Hostel Booking</a>
        <span class="text-white">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
    </div>
</nav>

<div class="container">
    <div class="card payment-card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Complete Your Payment</h4>
        </div>
        <div class="card-body">
            <h5><?php echo htmlspecialchars($room_name); ?></h5>
            <hr>
            <div class="mb-3">
                <strong>Guest Name:</strong> <?php echo htmlspecialchars($user['username']); ?>
            </div>
            <div class="mb-3">
                <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
            </div>
            <div class="mb-3">
                <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?>
            </div>
            <hr>
            <div class="mb-4">
                <h4>Total Amount: ₹<?php echo number_format($price, 2); ?></h4>
            </div>
            <button type="button" class="btn btn-success btn-lg w-100" onclick="payNow()">
                Pay ₹<?php echo number_format($price, 2); ?> Now
            </button>
            <a href="rooms1.php" class="btn btn-link w-100 mt-2">Cancel</a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function payNow() {
    var amount = <?php echo $price * 100; ?>; // Convert to paise
    var room_id = <?php echo $room_id; ?>;
    
    var options = {
        "key": "<?php echo $razorpay_key_id; ?>",
        "amount": amount,
        "currency": "INR",
        "name": "Hostel Booking",
        "description": "<?php echo htmlspecialchars($room_name); ?>",
        "image": "https://your-logo-url.com/logo.png", // Optional: Add your logo
        "handler": function (response) {
            // Payment successful
            $.ajax({
                url: 'process_payment.php',
                type: 'POST',
                data: {
                    payment_id: response.razorpay_payment_id,
                    room_id: room_id,
                    amount: <?php echo $price; ?>,
                    user_id: <?php echo $_SESSION['user_id']; ?>
                },
                success: function(result) {
                    window.location.href = 'payment_success.php?payment_id=' + response.razorpay_payment_id;
                },
                error: function() {
                    alert('Error processing payment. Please contact support.');
                }
            });
        },
        "prefill": {
            "name": "<?php echo htmlspecialchars($user['username']); ?>",
            "email": "<?php echo htmlspecialchars($user['email']); ?>",
            "contact": "<?php echo htmlspecialchars($user['phone']); ?>"
        },
        "theme": {
            "color": "#0d6efd"
        },
        "modal": {
            "ondismiss": function() {
                alert('Payment cancelled. You can try again.');
            }
        }
    };
    
    var rzp = new Razorpay(options);
    rzp.on('payment.failed', function (response) {
        alert('Payment Failed: ' + response.error.description);
        window.location.href = 'payment_failed.php?room_id=' + room_id;
    });
    
    rzp.open();
}
</script>
</body>
</html>
