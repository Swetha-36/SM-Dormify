<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['reg_id']) || $_SESSION['reg_id'] == 0) {
    header("Location: index.php?openLogin=true");
    exit();
}

// Get details from URL
if (!isset($_GET['hostel_id']) || !isset($_GET['price'])) {
    header("Location: rooms1.php");
    exit();
}

$hostel_id = (int)$_GET['hostel_id'];
$price = (float)$_GET['price'];

// Get user details
$stmt = $conn->prepare("SELECT * FROM register WHERE reg_id = ?");
$stmt->bind_param("i", $_SESSION['reg_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Get hostel details
$stmt_hostel = $conn->prepare("SELECT hostel_name FROM hostel WHERE id = ?");
$stmt_hostel->bind_param("i", $hostel_id);
$stmt_hostel->execute();
$result_hostel = $stmt_hostel->get_result();
$hostel = $result_hostel->fetch_assoc();
$hostel_name = $hostel ? $hostel['hostel_name'] : 'Hostel Room';
$stmt_hostel->close();

// Generate unique order ID
$order_id = "ORDER_" . $hostel_id . "_" . time();

// Your UPI ID
$upi_id = "yourname@paytm";  // Change to your UPI ID
$payee_name = "Hostel Booking";

// Generate UPI payment link
$upi_url = "upi://pay?pa=" . $upi_id . "&pn=" . urlencode($payee_name) . "&am=" . $price . "&cu=INR&tn=" . urlencode("Booking: " . $hostel_name . " - " . $order_id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment - Hostel Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .payment-card {
            max-width: 700px;
            margin: 50px auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .custom-navbar {
            background-color: #0f1a2c !important;
        }
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .payment-method:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        .payment-method.active {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }
        .payment-icon {
            font-size: 40px;
            width: 60px;
            text-align: center;
        }
        .payment-details {
            flex: 1;
        }
        .payment-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .payment-form.active {
            display: block;
        }
        .qr-section {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="rooms1.php">Hostel Booking</a>
        <span class="text-white">Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
    </div>
</nav>

<div class="container">
    <div class="card payment-card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-credit-card me-2"></i>Complete Your Payment</h4>
        </div>
        <div class="card-body">
            <!-- Booking Summary -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <h5><?php echo htmlspecialchars($hostel_name); ?></h5>
                    <p class="text-muted mb-1">
                        <i class="bi bi-person"></i> <?php echo htmlspecialchars($user['name']); ?>
                    </p>
                    <p class="text-muted mb-0">
                        <i class="bi bi-receipt"></i> Order ID: <code><?php echo $order_id; ?></code>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <h3 class="text-success mb-0">₹<?php echo number_format($price, 2); ?></h3>
                    <small class="text-muted">Total Amount</small>
                </div>
            </div>
            
            <hr>
            
            <h6 class="mb-3">Select Payment Method:</h6>

            <!-- UPI Payment -->
            <div class="payment-method" onclick="selectPayment('upi')">
                <div class="payment-icon" style="color: #5f259f;">
                    <i class="bi bi-phone"></i>
                </div>
                <div class="payment-details">
                    <h6 class="mb-1">UPI / QR Code</h6>
                    <small class="text-muted">Pay using PhonePe, Google Pay, Paytm or any UPI app</small>
                </div>
                <i class="bi bi-chevron-right"></i>
            </div>

            <!-- Card Payment -->
            <div class="payment-method" onclick="selectPayment('card')">
                <div class="payment-icon" style="color: #0d6efd;">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="payment-details">
                    <h6 class="mb-1">Debit / Credit Card</h6>
                    <small class="text-muted">Visa, Mastercard, RuPay cards accepted</small>
                </div>
                <i class="bi bi-chevron-right"></i>
            </div>

            <!-- Net Banking -->
            <div class="payment-method" onclick="selectPayment('netbanking')">
                <div class="payment-icon" style="color: #198754;">
                    <i class="bi bi-bank"></i>
                </div>
                <div class="payment-details">
                    <h6 class="mb-1">Net Banking</h6>
                    <small class="text-muted">Pay directly from your bank account</small>
                </div>
                <i class="bi bi-chevron-right"></i>
            </div>

            <!-- Wallet -->
            <div class="payment-method" onclick="selectPayment('wallet')">
                <div class="payment-icon" style="color: #fd7e14;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="payment-details">
                    <h6 class="mb-1">Wallets</h6>
                    <small class="text-muted">Paytm, PhonePe, Amazon Pay, Mobikwik</small>
                </div>
                <i class="bi bi-chevron-right"></i>
            </div>

            <!-- Cash on Arrival -->
            <div class="payment-method" onclick="selectPayment('cash')">
                <div class="payment-icon" style="color: #6c757d;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="payment-details">
                    <h6 class="mb-1">Pay at Hostel</h6>
                    <small class="text-muted">Pay when you check-in</small>
                </div>
                <i class="bi bi-chevron-right"></i>
            </div>

            <!-- UPI Form -->
            <div class="payment-form" id="upi-form">
                <h6><i class="bi bi-phone me-2"></i>UPI Payment</h6>
                <div class="qr-section">
                    <p class="mb-2">Scan QR Code with any UPI app</p>
                    <div id="qrcode" style="margin: 15px auto; width: 200px; height: 200px;"></div>
                    <div class="alert alert-info mt-3">
                        <strong>UPI ID:</strong> <?php echo $upi_id; ?>
                        <button class="btn btn-sm btn-primary float-end" onclick="copyUPI()">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                    <button class="btn btn-success" onclick="window.location.href='<?php echo $upi_url; ?>'">
                        <i class="bi bi-phone me-2"></i>Open UPI App
                    </button>
                </div>
                <form method="POST" action="verify_payment.php" class="mt-3">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $price; ?>">
                    <input type="hidden" name="payment_method" value="UPI">
                    
                    <label class="form-label">Enter UPI Transaction ID / UTR *</label>
                    <input type="text" name="transaction_id" class="form-control mb-3" placeholder="12-digit transaction ID" required>
                    <button type="submit" class="btn btn-primary w-100">Confirm Payment</button>
                </form>
            </div>

            <!-- Card Form -->
            <div class="payment-form" id="card-form">
                <h6><i class="bi bi-credit-card me-2"></i>Card Payment</h6>
                <form method="POST" action="verify_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $price; ?>">
                    <input type="hidden" name="payment_method" value="Card">
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle"></i> After making card payment, enter your transaction details below:
                    </div>
                    
                    <label class="form-label">Transaction ID / Reference Number *</label>
                    <input type="text" name="transaction_id" class="form-control mb-3" placeholder="Enter transaction reference number" required>
                    
                    <label class="form-label">Last 4 digits of card (optional)</label>
                    <input type="text" name="card_last4" class="form-control mb-3" placeholder="XXXX" maxlength="4">
                    
                    <button type="submit" class="btn btn-primary w-100">Confirm Payment</button>
                </form>
            </div>

            <!-- Net Banking Form -->
            <div class="payment-form" id="netbanking-form">
                <h6><i class="bi bi-bank me-2"></i>Net Banking</h6>
                <form method="POST" action="verify_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $price; ?>">
                    <input type="hidden" name="payment_method" value="Net Banking">
                    
                    <div class="alert alert-info mb-3">
                        <strong>Bank Details for NEFT/RTGS/IMPS:</strong><br>
                        Account Name: Hostel Booking<br>
                        Account Number: 1234567890<br>
                        IFSC Code: SBIN0001234<br>
                        Bank: State Bank of India
                    </div>
                    
                    <label class="form-label">Transaction ID / UTR Number *</label>
                    <input type="text" name="transaction_id" class="form-control mb-3" placeholder="Enter transaction ID" required>
                    
                    <button type="submit" class="btn btn-primary w-100">Confirm Payment</button>
                </form>
            </div>

            <!-- Wallet Form -->
            <div class="payment-form" id="wallet-form">
                <h6><i class="bi bi-wallet2 me-2"></i>Wallet Payment</h6>
                <form method="POST" action="verify_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $price; ?>">
                    <input type="hidden" name="payment_method" value="Wallet">
                    
                    <label class="form-label">Select Wallet</label>
                    <select class="form-select mb-3" name="wallet_type" required>
                        <option value="">Choose wallet...</option>
                        <option value="Paytm">Paytm</option>
                        <option value="PhonePe">PhonePe</option>
                        <option value="Amazon Pay">Amazon Pay</option>
                        <option value="Mobikwik">Mobikwik</option>
                        <option value="Freecharge">Freecharge</option>
                    </select>
                    
                    <label class="form-label">Transaction ID *</label>
                    <input type="text" name="transaction_id" class="form-control mb-3" placeholder="Enter wallet transaction ID" required>
                    
                    <button type="submit" class="btn btn-primary w-100">Confirm Payment</button>
                </form>
            </div>

            <!-- Cash Form -->
            <div class="payment-form" id="cash-form">
                <h6><i class="bi bi-cash-stack me-2"></i>Pay at Hostel</h6>
                <form method="POST" action="verify_payment.php">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $price; ?>">
                    <input type="hidden" name="payment_method" value="Cash">
                    <input type="hidden" name="transaction_id" value="CASH_<?php echo time(); ?>">
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> You will pay ₹<?php echo number_format($price, 2); ?> when you check-in at the hostel.
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="cashAgree" required>
                        <label class="form-check-label" for="cashAgree">
                            I agree to pay the full amount at check-in
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Confirm Booking</button>
                </form>
            </div>

            <button type="button" class="btn btn-outline-secondary w-100 mt-3" onclick="window.location.href='rooms1.php'">
                <i class="bi bi-arrow-left me-2"></i>Cancel & Go Back
            </button>
        </div>
    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script>
// Select payment method
function selectPayment(method) {
    // Remove active class from all
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.payment-form').forEach(el => el.classList.remove('active'));
    
    // Add active class to selected
    event.currentTarget.classList.add('active');
    document.getElementById(method + '-form').classList.add('active');
    
    // Generate QR for UPI
    if (method === 'upi' && !document.querySelector('#qrcode canvas')) {
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $upi_url; ?>",
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }
}

// Copy UPI ID
function copyUPI() {
    var upiId = "<?php echo $upi_id; ?>";
    navigator.clipboard.writeText(upiId).then(function() {
        alert('UPI ID copied: ' + upiId);
    });
}
</script>
</body>
</html>
