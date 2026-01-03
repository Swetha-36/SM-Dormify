<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $hostel_id = (int)$_POST['hostel_id'];
    $amount = (float)$_POST['amount'];
    $transaction_id = trim($_POST['transaction_id']);
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['reg_id'];
    
    // Additional fields
    $wallet_type = isset($_POST['wallet_type']) ? $_POST['wallet_type'] : '';
    $card_last4 = isset($_POST['card_last4']) ? $_POST['card_last4'] : '';
    
    // Create bookings table if doesn't exist
    $create_table = "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        hostel_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        order_id VARCHAR(100) NOT NULL,
        transaction_id VARCHAR(100),
        payment_method VARCHAR(50),
        payment_status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($create_table);
    
    // Determine payment status
    $payment_status = ($payment_method == 'Cash') ? 'confirmed' : 'pending';
    
    // Save booking
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, hostel_id, amount, order_id, transaction_id, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iidssss", $user_id, $hostel_id, $amount, $order_id, $transaction_id, $payment_method, $payment_status);

    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: payment_success.php?order_id=" . urlencode($order_id) . "&amount=" . urlencode($amount) . "&status=" . $payment_status . "&method=" . urlencode($payment_method));
        exit();
    } else {
        $stmt->close();
        header("Location: payment_failed.php?reason=Database error");
        exit();
    }
}
?>
