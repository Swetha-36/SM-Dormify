<?php
// Include configuration files
require_once 'config.php';
require_once 'razorpay_config.php';

// Install Razorpay PHP SDK using: composer require razorpay/razorpay
// Download from: https://github.com/razorpay/razorpay-php
require_once 'razorpay-php/Razorpay.php';

use Razorpay\Api\Api;

// Set response header to JSON
header('Content-Type: application/json');

// Allow CORS for local testing (remove in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Get POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    // Validate input data
    if (!isset($data['userName']) || !isset($data['userEmail']) || 
        !isset($data['userPhone']) || !isset($data['hostelName']) || 
        !isset($data['roomType']) || !isset($data['totalAmount'])) {
        throw new Exception('Missing required fields');
    }
    
    // Sanitize input
    $userName = mysqli_real_escape_string($conn, trim($data['userName']));
    $userEmail = mysqli_real_escape_string($conn, trim($data['userEmail']));
    $userPhone = mysqli_real_escape_string($conn, trim($data['userPhone']));
    $hostelName = mysqli_real_escape_string($conn, trim($data['hostelName']));
    $roomType = mysqli_real_escape_string($conn, trim($data['roomType']));
    $totalAmount = floatval($data['totalAmount']);
    
    // Validate amount
    if ($totalAmount <= 0) {
        throw new Exception('Invalid amount');
    }
    
    // Check if user exists, if not create new user
    $userQuery = "SELECT id FROM users WHERE email = '$userEmail'";
    $userResult = mysqli_query($conn, $userQuery);
    
    if (mysqli_num_rows($userResult) > 0) {
        $user = mysqli_fetch_assoc($userResult);
        $userId = $user['id'];
    } else {
        // Create new user
        $insertUser = "INSERT INTO users (name, email, phone) VALUES ('$userName', '$userEmail', '$userPhone')";
        if (!mysqli_query($conn, $insertUser)) {
            throw new Exception('Failed to create user');
        }
        $userId = mysqli_insert_id($conn);
    }
    
    // Initialize Razorpay API
    $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
    
    // Convert amount to paise (Razorpay expects amount in smallest currency unit)
    $amountInPaise = $totalAmount * 100;
    
    // Create Razorpay order
    $orderData = [
        'receipt'         => 'booking_' . time(),
        'amount'          => $amountInPaise,
        'currency'        => CURRENCY,
        'payment_capture' => 1 // Auto capture payment
    ];
    
    $razorpayOrder = $api->order->create($orderData);
    $razorpayOrderId = $razorpayOrder['id'];
    
    // Insert booking record with pending status
    $insertBooking = "INSERT INTO bookings (user_id, hostel_name, room_type, total_amount, booking_status, razorpay_order_id) 
                      VALUES ('$userId', '$hostelName', '$roomType', '$totalAmount', 'pending', '$razorpayOrderId')";
    
    if (!mysqli_query($conn, $insertBooking)) {
        throw new Exception('Failed to create booking');
    }
    
    $bookingId = mysqli_insert_id($conn);
    
    // Insert payment record with pending status
    $insertPayment = "INSERT INTO payments (booking_id, razorpay_order_id, payment_status, amount) 
                      VALUES ('$bookingId', '$razorpayOrderId', 'pending', '$totalAmount')";
    
    if (!mysqli_query($conn, $insertPayment)) {
        throw new Exception('Failed to create payment record');
    }
    
    // Return success response with order details
    echo json_encode([
        'success' => true,
        'order' => [
            'id' => $razorpayOrderId,
            'amount' => $amountInPaise,
            'currency' => CURRENCY,
            'key_id' => RAZORPAY_KEY_ID // Safe to send to frontend
        ],
        'booking_id' => $bookingId
    ]);
    
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close database connection
mysqli_close($conn);
?>
