<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_id = $_POST['payment_id'];
    $room_id = $_POST['room_id'];
    $amount = $_POST['amount'];
    $user_id = $_POST['user_id'];
    
    try {
        // Insert booking record
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, total_amount, booking_status, created_at) VALUES (?, ?, ?, 'confirmed', NOW())");
        $stmt->execute([$user_id, $room_id, $amount]);
        $booking_id = $conn->lastInsertId();
        
        // Insert payment record
        $stmt = $conn->prepare("INSERT INTO payments (booking_id, payment_id, amount, payment_status, paid_at) VALUES (?, ?, ?, 'paid', NOW())");
        $stmt->execute([$booking_id, $payment_id, $amount]);
        
        // Update room status to booked
        $stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
        $stmt->execute([$room_id]);
        
        echo json_encode(['status' => 'success', 'booking_id' => $booking_id]);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
