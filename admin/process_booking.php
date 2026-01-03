<?php
require_once 'db.php';

// Get data from booking form
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$hostel_id = $_POST['hostel_id'];
$amount = $_POST['amount'];
$order_id = $_POST['order_id'];
$transaction_id = $_POST['transaction_id'] ?? null;
$payment_method = $_POST['payment_method'] ?? 'online';
$payment_status = 'pending';

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
$stmt->bind_param("ss", $phone, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
} else {
    // Create new user
    $stmt = $conn->prepare("INSERT INTO users (name, phone, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $phone, $email);
    $stmt->execute();
    $user_id = $conn->insert_id;
}
$stmt->close();

// Create booking
$stmt = $conn->prepare("INSERT INTO bookings (user_id, hostel_id, amount, order_id, transaction_id, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iidssss", $user_id, $hostel_id, $amount, $order_id, $transaction_id, $payment_method, $payment_status);

if ($stmt->execute()) {
    header("Location: booking_success.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
?>
