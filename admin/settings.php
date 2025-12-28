<?php
require_once 'check_auth.php';
require_once 'db.php';

$message = '';

// Get current settings
$result = $conn->query("SELECT * FROM settings LIMIT 1");
$settings = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_number = trim($_POST['contact_number']);
    $hostel_rules = trim($_POST['hostel_rules']);
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];
    
    $stmt = $conn->prepare("UPDATE settings SET contact_number=?, hostel_rules=?, check_in_time=?, check_out_time=? WHERE id=?");
    $stmt->bind_param("ssssi", $contact_number, $hostel_rules, $check_in_time, $check_out_time, $settings['id']);
    
    if ($stmt->execute()) {
        $message = 'Settings updated successfully!';
        // Refresh settings
        $result = $conn->query("SELECT * FROM settings LIMIT 1");
        $settings = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Settings</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Contact Number *</label>
                <input type="text" name="contact_number" value="<?php echo htmlspecialchars($settings['contact_number']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Hostel Rules</label>
                <textarea name="hostel_rules" rows="6"><?php echo htmlspecialchars($settings['hostel_rules']); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Check-in Time *</label>
                    <input type="time" name="check_in_time" value="<?php echo $settings['check_in_time']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Check-out Time *</label>
                    <input type="time" name="check_out_time" value="<?php echo $settings['check_out_time']; ?>" required>
                </div>
            </div>
            
            <button type="submit" class="btn">Update Settings</button>
        </form>
    </div>
</body>
</html>
