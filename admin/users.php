<?php
require_once 'check_auth.php';
require_once 'db.php';

$user_bookings = [];

// Get user booking history if requested
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
    
    // Fetch user details from register table first
    $user_stmt = $conn->prepare("SELECT reg_id, name, phoneno, email FROM register WHERE reg_id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_details = $user_stmt->get_result()->fetch_assoc();
    $user_stmt->close();
    
    // Get user bookings - assuming bookings table uses reg_id as user_id

$stmt = $conn->prepare("SELECT b.*, r.room_type FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_bookings = $stmt->get_result();
    $stmt->close();
}

// Get all users from register table
$users = $conn->query("SELECT reg_id as id, name, phoneno, email FROM register ORDER BY reg_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Keep your existing CSS - no changes */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3498db; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .btn-small { padding: 5px 10px; margin: 0 5px; text-decoration: none; border-radius: 3px; font-size: 14px; background: #3498db; color: white; }
        .btn-small:hover { background: #2980b9; }
        .booking-section { margin-top: 40px; }
        .back-link { display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #95a5a6; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>User Management</h1>
        
        <!-- Users List -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Registered On</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php if ($users && $users->num_rows > 0): ?>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['phoneno']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        
                        <td>
                            <a href="?user_id=<?php echo $user['id']; ?>" class="btn-small">View Bookings</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 40px;">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- User Booking History -->
        <?php if (isset($_GET['user_id']) && $user_id > 0): ?>
            <div class="booking-section">
                <?php if ($user_details): ?>
                    <a href="?" class="back-link">← Back to Users</a>
                    <h2>Bookings for: <?php echo htmlspecialchars($user_details['name']); ?> 
                        <small>(ID: <?php echo $user_details['reg_id']; ?>)</small>
                    </h2>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_details['phoneno']); ?> | 
                       <strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></p>
                <?php endif; ?>
                
                <?php if ($user_bookings && $user_bookings->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Room Type</th>
                                <th>Room Number</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Beds</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $user_bookings->data_seek(0); // Reset pointer ?>
                            <?php while ($booking = $user_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $booking['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($booking['room_type']); ?></td>
                                <td><?php echo htmlspecialchars($booking['room_number'] ?? 'N/A'); ?></td>
                                <td><?php echo date('d M Y', strtotime($booking['check_in_date'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($booking['check_out_date'])); ?></td>
                                <td><?php echo $booking['beds_booked']; ?></td>
                                <td><strong>₹<?php echo number_format($booking['total_amount'], 2); ?></strong></td>
                                <td>
                                    <?php 
                                    $status = $booking['status'];
                                    $status_class = $status == 'Confirmed' ? 'confirmed' : ($status == 'Pending' ? 'pending' : 'cancelled');
                                    echo "<span style='padding: 4px 8px; border-radius: 12px; background: " . 
                                         ($status_class == 'confirmed' ? '#d4edda' : ($status_class == 'pending' ? '#fff3cd' : '#f8d7da')) . "; color: " .
                                         ($status_class == 'confirmed' ? '#155724' : ($status_class == 'pending' ? '#856404' : '#721c24')) . "; font-size: 12px; font-weight: bold;'>" . 
                                         htmlspecialchars($status) . "</span>";
                                    ?>
                                </td>
                                
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; margin-top: 20px;">
                        <h4>No bookings found for this user</h4>
                        <p>This user has not made any bookings yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
