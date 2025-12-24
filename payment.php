<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['room_id'])) {
    header('Location: index.php');
    exit;
}

$roomId = intval($_POST['room_id']);


$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND is_available = 1");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$room = $result->fetch_assoc();
$_SESSION['booking_room'] = $room;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Complete Your Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: Arial, sans-serif;
            min-height: 100vh;
            padding: 40px 0;
        }

        .payment-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .payment-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .payment-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .payment-body {
            padding: 40px;
        }

        .booking-summary {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 35px;
            border-left: 4px solid #667eea;
        }

        .booking-summary h4 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-row:last-child {
            border-bottom: none;
            padding-top: 20px;
            margin-top: 10px;
            border-top: 2px solid #667eea;
            font-size: 20px;
            font-weight: 700;
        }

        .summary-label {
            color: #666;
            font-weight: 500;
        }

        .summary-value {
            color: #333;
            font-weight: 600;
        }

        .total-value {
            color: #667eea;
            font-size: 26px;
        }

        .form-section-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin: 35px 0 25px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .card-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card-icons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .card-icon {
            width: 45px;
            height: 30px;
            background: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            color: #666;
        }

        .pay-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 30px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .pay-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .secure-badge {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .secure-badge i {
            color: #28a745;
            margin-right: 5px;
        }

        .back-link {
            display: inline-block;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #f0f0f0;
            transform: translateX(-5px);
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <a href="room_details.php?id=<?php echo $room['id']; ?>" class="back-link">← Back to Room Details</a>
        
        <div class="payment-card">
            <div class="payment-header">
                <h2>Complete Your Payment</h2>
                <p>Secure checkout for <?php echo htmlspecialchars($room['room_name']); ?></p>
            </div>
            
            <div class="payment-body">
                <!-- Booking Summary -->
                <div class="booking-summary">
                    <h4>📋 Booking Summary</h4>
                    
                    <div class="summary-row">
                        <span class="summary-label">Room Name:</span>
                        <span class="summary-value"><?php echo htmlspecialchars($room['room_name']); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Room Type:</span>
                        <span class="summary-value"><?php echo ucfirst($room['room_type']); ?> Sharing</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Location:</span>
                        <span class="summary-value"><?php echo htmlspecialchars($room['location']); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Total Amount:</span>
                        <span class="summary-value total-value">₹<?php echo number_format($room['price'], 0); ?></span>
                    </div>
                </div>
                
                <!-- Payment Form -->
                <form action="process_payment.php" method="POST" id="paymentForm">
                    <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                    
                    <!-- Personal Information -->
                    <h3 class="form-section-title">👤 Personal Information</h3>
                    
                    <div class="form-group">
                        <label for="customer_name">Full Name *</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control" required placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_email">Email Address *</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-control" required placeholder="your@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_phone">Phone Number *</label>
                        <input type="tel" id="customer_phone" name="customer_phone" class="form-control" required placeholder="+91 1234567890" pattern="[0-9+\s-]{10,15}">
                    </div>
                    
                    <!-- Card Details -->
                    <h3 class="form-section-title">💳 Card Details</h3>
                    
                    <div class="form-group">
                        <label for="card_name">Cardholder Name *</label>
                        <input type="text" id="card_name" name="card_name" class="form-control" required placeholder="Name as on card">
                    </div>
                    
                    <div class="form-group">
                        <label for="card_number">Card Number *</label>
                        <input type="text" id="card_number" name="card_number" class="form-control" required placeholder="1234 5678 9012 3456" maxlength="19" pattern="[0-9\s]{13,19}">
                        <div class="card-icons">
                            <div class="card-icon">VISA</div>
                            <div class="card-icon">MC</div>
                            <div class="card-icon">AMEX</div>
                            <div class="card-icon">RUPAY</div>
                        </div>
                    </div>
                    
                    <div class="card-row">
                        <div class="form-group">
                            <label for="expiry">Expiry Date *</label>
                            <input type="text" id="expiry" name="expiry" class="form-control" required placeholder="MM/YY" maxlength="5" pattern="[0-9]{2}/[0-9]{2}">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV *</label>
                            <input type="text" id="cvv" name="cvv" class="form-control" required placeholder="123" maxlength="3" pattern="[0-9]{3,4}">
                        </div>
                    </div>
                    
                    <button type="submit" class="pay-btn">
                        Pay ₹<?php echo number_format($room['price'], 0); ?>
                    </button>
                    
                    <div class="secure-badge">
                        <i>🔒</i> Your payment is secure and encrypted
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-format card number
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Auto-format expiry date
        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            if (cardNumber.length < 13 || cardNumber.length > 19) {
                e.preventDefault();
                alert('Please enter a valid card number');
                return false;
            }
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
