<?php
require_once 'check_auth.php';
require_once 'db.php';

$message = '';
$edit_room = null;

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = 'Room deleted successfully!';
    }
    $stmt->close();
}

// Handle edit request
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_room = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_type = $_POST['room_type'];
    $total_beds = (int)$_POST['total_beds'];
    $available_beds = (int)$_POST['available_beds'];
    $price_per_bed = (float)$_POST['price_per_bed'];
    $ac_type = $_POST['ac_type'];
    $status = $_POST['status'];
    
    if (isset($_POST['room_id']) && $_POST['room_id']) {
        // Update
        $id = (int)$_POST['room_id'];
        $stmt = $conn->prepare("UPDATE rooms SET room_type=?, total_beds=?, available_beds=?, price_per_bed=?, ac_type=?, status=? WHERE id=?");
        $stmt->bind_param("siidssi", $room_type, $total_beds, $available_beds, $price_per_bed, $ac_type, $status, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO rooms (room_type, total_beds, available_beds, price_per_bed, ac_type, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siidss", $room_type, $total_beds, $available_beds, $price_per_bed, $ac_type, $status);
    }
    
    if ($stmt->execute()) {
        $message = 'Room saved successfully!';
        $edit_room = null;
    }
    $stmt->close();
}

// Get all rooms
$rooms = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room Management - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Room Management</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <h2><?php echo $edit_room ? 'Edit' : 'Add New'; ?> Room</h2>
        <form method="POST" class="form-container">
            <input type="hidden" name="room_id" value="<?php echo $edit_room ? $edit_room['id'] : ''; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Room Type *</label>
                    <select name="room_type" required>
                        <option value="Single" <?php echo ($edit_room && $edit_room['room_type']=='Single')?'selected':''; ?>>Single</option>
                        <option value="Double" <?php echo ($edit_room && $edit_room['room_type']=='Double')?'selected':''; ?>>Double</option>
                        <option value="Triple" <?php echo ($edit_room && $edit_room['room_type']=='Triple')?'selected':''; ?>>Triple</option>
                        <option value="Common" <?php echo ($edit_room && $edit_room['room_type']=='Common')?'selected':''; ?>>Common</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Total Beds *</label>
                    <input type="number" name="total_beds" value="<?php echo $edit_room ? $edit_room['total_beds'] : ''; ?>" required min="1">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Available Beds *</label>
                    <input type="number" name="available_beds" value="<?php echo $edit_room ? $edit_room['available_beds'] : ''; ?>" required min="0">
                </div>
                
                <div class="form-group">
                    <label>Price Per Bed *</label>
                    <input type="number" name="price_per_bed" step="0.01" value="<?php echo $edit_room ? $edit_room['price_per_bed'] : ''; ?>" required min="0">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>AC Type *</label>
                    <select name="ac_type" required>
                        <option value="AC" <?php echo ($edit_room && $edit_room['ac_type']=='AC')?'selected':''; ?>>AC</option>
                        <option value="Non-AC" <?php echo ($edit_room && $edit_room['ac_type']=='Non-AC')?'selected':''; ?>>Non-AC</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="Available" <?php echo ($edit_room && $edit_room['status']=='Available')?'selected':''; ?>>Available</option>
                        <option value="Maintenance" <?php echo ($edit_room && $edit_room['status']=='Maintenance')?'selected':''; ?>>Maintenance</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn"><?php echo $edit_room ? 'Update' : 'Add'; ?> Room</button>
            <?php if ($edit_room): ?>
                <a href="rooms.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
        
        <h2>All Rooms</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Total Beds</th>
                    <th>Available</th>
                    <th>Price/Bed</th>
                    <th>AC Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($room = $rooms->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $room['id']; ?></td>
                    <td><?php echo $room['room_type']; ?></td>
                    <td><?php echo $room['total_beds']; ?></td>
                    <td><?php echo $room['available_beds']; ?></td>
                    <td>₹<?php echo number_format($room['price_per_bed'], 2); ?></td>
                    <td><?php echo $room['ac_type']; ?></td>
                    <td><span class="status-<?php echo strtolower($room['status']); ?>"><?php echo $room['status']; ?></span></td>
                    <td>
                        <a href="?edit=<?php echo $room['id']; ?>" class="btn-small">Edit</a>
                        <a href="?delete=<?php echo $room['id']; ?>" class="btn-small btn-danger" onclick="return confirm('Delete this room?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
