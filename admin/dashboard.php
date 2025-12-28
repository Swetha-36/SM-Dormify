<?php
require_once 'check_auth.php';
require_once 'db.php';

// Get total rooms
$rooms_result = $conn->query("SELECT COUNT(*) as total FROM rooms");
$total_rooms = $rooms_result->fetch_assoc()['total'];

// Get total bookings
$bookings_result = $conn->query("SELECT COUNT(*) as total FROM bookings");
$total_bookings = $bookings_result->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Hostel Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Dashboard</h1>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Rooms</h3>
                <p class="stat-number"><?php echo $total_rooms; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Bookings</h3>
                <p class="stat-number"><?php echo $total_bookings; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
