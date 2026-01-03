<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SM-Rooms(Admin)</title>
</head>
<body>
    <?php
require_once 'check_auth.php';
require_once 'db.php';

$message = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$hostel_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Delete Action
if ($action == 'delete' && $hostel_id > 0) {
    $stmt = $conn->prepare("DELETE FROM hostel WHERE id = ?");
    $stmt->bind_param("i", $hostel_id);
    if ($stmt->execute()) {
        $message = 'Hostel deleted successfully!';
    } else {
        $message = 'Error deleting hostel';
    }
    $stmt->close();
    $action = 'list';
}

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hostel_name = trim($_POST['hostel_name']);
    $location = trim($_POST['location']);
    $hostel_type = $_POST['hostel_type'];
    $food = $_POST['food'];
    $occupancy_type = $_POST['occupancy_type'];
    $room_type = $_POST['room_type'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $amenities = trim($_POST['amenities']);
    $edit_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    // Get existing image path if editing
    $image_path = '';
    if ($edit_id > 0) {
        $result = $conn->query("SELECT image_path FROM hostel WHERE id = $edit_id");
        if ($row = $result->fetch_assoc()) {
            $image_path = $row['image_path'];
        }
    }
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_path = $upload_dir . time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }
    
    if ($edit_id > 0) {
        // Update existing hostel
        $stmt = $conn->prepare("UPDATE hostel SET hostel_name=?, location=?, hostel_type=?, food=?, occupancy_type=?, room_type=?, price=?, description=?, amenities=?, image_path=? WHERE id=?");
        $stmt->bind_param("ssssssdsssi", $hostel_name, $location, $hostel_type, $food, $occupancy_type, $room_type, $price, $description, $amenities, $image_path, $edit_id);
    } else {
        // Insert new hostel
        $stmt = $conn->prepare("INSERT INTO hostel (hostel_name, location, hostel_type, food, occupancy_type, room_type, price, description, amenities, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdsss", $hostel_name, $location, $hostel_type, $food, $occupancy_type, $room_type, $price, $description, $amenities, $image_path);
    }
    
    if ($stmt->execute()) {
        $message = $edit_id > 0 ? 'Hostel updated successfully!' : 'Hostel added successfully!';
        $action = 'list';
    } else {
        $message = 'Error saving hostel details';
    }
    $stmt->close();
}

// Fetch hostel for editing
$hostel = null;
if ($action == 'edit' && $hostel_id > 0) {
    $result = $conn->query("SELECT * FROM hostel WHERE id = $hostel_id");
    if ($result->num_rows > 0) {
        $hostel = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hostel Management - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hostel-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .hostel-table th, .hostel-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .hostel-table th {
            background-color: #3498db;
            color: white;
        }
        .hostel-table tr:hover {
            background-color: #f5f5f5;
        }
        .action-buttons a {
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #2ecc71;
            color: white;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-add {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-back {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #3498db;
        }
        .hostel-image {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Hostel Management</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($action == 'list'): ?>
            <!-- List View -->
            <a href="?action=add" class="btn-add">+ Add New Hostel</a>
            
            <table class="hostel-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Hostel Name</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Food</th>
                        <th>Occupancy</th>
                        <th>Room Type</th>
                        <th>Price (₹)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM hostel ORDER BY id DESC");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>";
                            if (!empty($row['image_path']) && file_exists($row['image_path'])) {
                                echo "<img src='" . htmlspecialchars($row['image_path']) . "' class='hostel-image' alt='Hostel'>";
                            } else {
                                echo "No Image";
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row['hostel_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['hostel_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['food']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['occupancy_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['room_type']) . "</td>";
                            echo "<td>₹" . number_format($row['price'], 2) . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a href='?action=edit&id=" . $row['id'] . "' class='btn-edit'>Edit</a>";
                            echo "<a href='?action=delete&id=" . $row['id'] . "' class='btn-delete' onclick=\"return confirm('Are you sure you want to delete this hostel?')\">Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' style='text-align:center;'>No hostels found. Add your first hostel!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
        <?php elseif ($action == 'add' || $action == 'edit'): ?>
            <!-- Add/Edit Form -->
            <a href="?action=list" class="btn-back">← Back to List</a>
            
            <form method="POST" enctype="multipart/form-data" class="form-container">
                <?php if ($hostel): ?>
                    <input type="hidden" name="id" value="<?php echo $hostel['id']; ?>">
                <?php endif; ?>
                
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
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Occupancy Type *</label>
                        <select name="occupancy_type" required>
                            <option value="">-- Select Occupancy --</option>
                            <option value="Student" <?php echo ($hostel && $hostel['occupancy_type']=='Student')?'selected':''; ?>>Student</option>
                            <option value="Working" <?php echo ($hostel && $hostel['occupancy_type']=='Working')?'selected':''; ?>>Working</option>
                            <option value="Both" <?php echo ($hostel && $hostel['occupancy_type']=='Both')?'selected':''; ?>>Both</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Room Type *</label>
                        <select name="room_type" required>
                            <option value="">-- Select Room Type --</option>
                            <option value="Single" <?php echo ($hostel && $hostel['room_type']=='Single')?'selected':''; ?>>Single</option>
                            <option value="Double" <?php echo ($hostel && $hostel['room_type']=='Double')?'selected':''; ?>>Double</option>
                            <option value="Triple" <?php echo ($hostel && $hostel['room_type']=='Triple')?'selected':''; ?>>Triple</option>
                            <option value="Common Sharing" <?php echo ($hostel && $hostel['room_type']=='Common Sharing')?'selected':''; ?>>Common Sharing</option>
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
                    <?php if ($hostel && !empty($hostel['image_path'])): ?>
                        <p style="color: #666; font-size: 14px; margin-top: 5px;">
                            Current: <?php echo basename($hostel['image_path']); ?>
                        </p>
                        <img src="<?php echo htmlspecialchars($hostel['image_path']); ?>" alt="Hostel" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn"><?php echo $hostel ? 'Update' : 'Add'; ?> Hostel</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
</body>
</html>
