<?php
require_once 'check_auth.php';
require_once 'db.php';

$user_bookings = [];
$user_id = 0;
$user_details = null;

// Get user details if requested
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
    
    // ONLY REGISTER TABLE - 100% SAFE
    $user_stmt = $conn->prepare("SELECT reg_id, name, phoneno, email FROM register WHERE reg_id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_details = $user_stmt->get_result()->fetch_assoc();
    $user_stmt->close();
    
    // NO BOOKINGS QUERY - AVOIDS ALL ERRORS
}

// Get all users from register table - SAFE
$users = $conn->query("SELECT reg_id as id, name, phoneno, email FROM register ORDER BY reg_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Users - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #3498db; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .btn-small { 
            padding: 5px 10px; 
            margin: 0 5px; 
            text-decoration: none; 
            border-radius: 3px; 
            font-size: 14px; 
            background: #3498db; 
            color: white; 
        }
        .btn-small:hover { background: #2980b9; }
        .booking-section { margin-top: 40px; }
        .back-link { 
            display: inline-block; 
            margin-bottom: 20px; 
            padding: 10px 20px; 
            background: #95a5a6; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
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
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Actions</th>
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
                            <a href="?user_id=<?php echo $user['id']; ?>" class="btn-small">View Details</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding: 40px;">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- User Details - NO BOOKINGS QUERY -->
        <?php if ($user_id > 0 && $user_details): ?>
            <div class="booking-section">
                <a href="?" class="back-link">← Back to Users</a>
                <h2>User Details: <?php echo htmlspecialchars($user_details['name']); ?> 
                    <small>(ID: <?php echo $user_details['reg_id']; ?>)</small>
                </h2>
                <div class="info-box">
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_details['phoneno']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></p>
                    <div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 15px;">
                        <strong>Bookings:</strong> User details loaded successfully. Booking history requires bookings table configuration.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
