<?php
require_once 'check_auth.php';
require_once 'db.php';

// Get all bookings with user details
$bookings = $conn->query("SELECT b.*, u.name as user_name, u.phone, u.email FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bookings - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>All Bookings</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Contact</th>
                    <th>Hostel ID</th>
                    <th>Amount</th>
                    <th>Order ID</th>
                    <th>Payment Status</th>
                    <th>Booked On</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bookings->num_rows > 0): ?>
                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $booking['id']; ?></td>
                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['phone']); ?><br><?php echo htmlspecialchars($booking['email']); ?></td>
                        <td><?php echo $booking['hostel_id']; ?></td>
                        <td>₹<?php echo number_format($booking['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($booking['order_id']); ?></td>
                        <td><span class="status-<?php echo strtolower($booking['payment_status']); ?>"><?php echo $booking['payment_status']; ?></span></td>
                        <td><?php echo date('d M Y', strtotime($booking['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8">No bookings found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
