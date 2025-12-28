<?php
require_once 'check_auth.php';
require_once 'db.php';

// Get all bookings with user and room details
$query = "SELECT b.*, u.name as user_name, u.email, u.phone, r.room_type 
          FROM bookings b 
          JOIN users u ON b.user_id = u.id 
          JOIN rooms r ON b.room_id = r.id 
          ORDER BY b.created_at DESC";
$bookings = $conn->query($query);
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
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Beds</th>
                    <th>Amount</th>
                    <th>Status</th>
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
                        <td><?php echo $booking['room_type']; ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['check_in_date'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['check_out_date'])); ?></td>
                        <td><?php echo $booking['beds_booked']; ?></td>
                        <td>₹<?php echo number_format($booking['total_amount'], 2); ?></td>
                        <td><span class="status-<?php echo strtolower($booking['status']); ?>"><?php echo $booking['status']; ?></span></td>
                        <td><?php echo date('d M Y', strtotime($booking['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="10">No bookings found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
