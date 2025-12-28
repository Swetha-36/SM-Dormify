<?php
require_once 'check_auth.php';
require_once 'db.php';

$message = '';
$hostel = null;

// Get existing hostel
$result = $conn->query("SELECT * FROM hostel LIMIT 1");
if ($result->num_rows > 0) {
    $hostel = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hostel_name = trim($_POST['hostel_name']);
    $location = trim($_POST['location']);
    $hostel_type = $_POST['hostel_type'];
    $food = $_POST['food'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $amenities = trim($_POST['amenities']);
    $image_path = $hostel ? $hostel['image_path'] : '';
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_path = $upload_dir . time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }
    
    if ($hostel) {
        // Update existing hostel
        $stmt = $conn->prepare("UPDATE hostel SET hostel_name=?, location=?, hostel_type=?, food=?, price=?, description=?, amenities=?, image_path=? WHERE id=?");
        $stmt->bind_param("ssssdsssi", $hostel_name, $location, $hostel_type, $food, $price, $description, $amenities, $image_path, $hostel['id']);
    } else {
        // Insert new hostel
        $stmt = $conn->prepare("INSERT INTO hostel (hostel_name, location, hostel_type, food, price, description, amenities, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdsss", $hostel_name, $location, $hostel_type, $food, $price, $description, $amenities, $image_path);
    }
    
    if ($stmt->execute()) {
        $message = 'Hostel details saved successfully!';
        // Refresh hostel data
        $result = $conn->query("SELECT * FROM hostel LIMIT 1");
        $hostel = $result->fetch_assoc();
    } else {
        $message = 'Error saving hostel details';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hostel Details - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        
        select:focus {
            outline: none;
            border-color: #3498db;
        }
        
        select option {
            padding: 10px;
        }
        
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Hostel Details</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label>Hostel Name *</label>
                <input type="text" name="hostel_name" value="<?php echo $hostel ? htmlspecialchars($hostel['hostel_name']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" value="<?php echo $hostel ? htmlspecialchars($hostel['location']) : ''; ?>" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Hostel Type *</label>
                    <select name="hostel_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Boys" <?php echo ($hostel && $hostel['hostel_type']=='Boys')?'selected':''; ?>>Boys</option>
                        <option value="Girls" <?php echo ($hostel && $hostel['hostel_type']=='Girls')?'selected':''; ?>>Girls</option>
                        <option value="Co-living" <?php echo ($hostel && $hostel['hostel_type']=='Co-living')?'selected':''; ?>>Co-living</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Food Options *</label>
                    <select name="food" required>
                        <option value="">-- Select Food Type --</option>
                        <option value="Veg" <?php echo ($hostel && $hostel['food']=='Veg')?'selected':''; ?>>Veg</option>
                        <option value="Non-Veg" <?php echo ($hostel && $hostel['food']=='Non-Veg')?'selected':''; ?>>Non-Veg</option>
                        <option value="Veg and Non-Veg" <?php echo ($hostel && $hostel['food']=='Veg and Non-Veg')?'selected':''; ?>>Veg and Non-Veg</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Price Per Bed (₹) *</label>
                <input type="number" name="price" step="0.01" min="0" value="<?php echo $hostel ? htmlspecialchars($hostel['price']) : ''; ?>" placeholder="5000" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?php echo $hostel ? htmlspecialchars($hostel['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Amenities (comma-separated)</label>
                <input type="text" name="amenities" value="<?php echo $hostel ? htmlspecialchars($hostel['amenities']) : ''; ?>" placeholder="WiFi, Parking, Laundry, AC">
            </div>
            
            <div class="form-group">
                <label>Hostel Image</label>
                <input type="file" name="image" accept="image/*">
                <?php if ($hostel && $hostel['image_path']): ?>
                    <p style="color: #666; font-size: 14px; margin-top: 5px;">
                        Current: <?php echo basename($hostel['image_path']); ?>
                    </p>
                    <img src="<?php echo $hostel['image_path']; ?>" alt="Hostel" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn"><?php echo $hostel ? 'Update' : 'Add'; ?> Hostel</button>
        </form>
    </div>
    </div> <!-- Close main-content -->
</body>
</html>
