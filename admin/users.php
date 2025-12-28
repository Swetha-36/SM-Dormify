<?php
require_once 'check_auth.php';
require_once 'db.php';

$user_bookings = [];

// Get user booking history if requested
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
    $stmt = $conn->prepare("SELECT b.*, r.room_type FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_bookings = $stmt->get_result();
    $stmt->close();
}

// Get all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>User Management</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users->num_rows > 0): ?>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="?user_id=<?php echo $user['id']; ?>" class="btn-small">View Bookings</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if (isset($_GET['user_id']) && $user_bookings->num_rows > 0): ?>
            <h2>Booking History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room Type</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Beds</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $user_bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['room_type']; ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['check_in_date'])); ?></td>
                        <td><?php echo date('d M Y', strtotime($booking['check_out_date'])); ?></td>
                        <td><?php echo $booking['beds_booked']; ?></td>
                        <td>₹<?php echo number_format($booking['total_amount'], 2); ?></td>
                        <td><?php echo $booking['status']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
