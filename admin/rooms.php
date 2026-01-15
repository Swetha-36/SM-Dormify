<?php
require_once 'check_auth.php';
require_once 'db.php';

$message = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Delete Action
if ($action == 'delete' && $room_id > 0) {
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    if ($stmt->execute()) {
        $message = 'Room deleted successfully!';
    } else {
        $message = 'Error deleting room';
    }
    $stmt->close();
    $action = 'list';
}


// Handle Form Submission (Add/Edit)

// 1. ALWAYS FIRST: Fetch room data BEFORE processing POST
$room = null;
if ($action == 'edit' && isset($room_id) && $room_id > 0) {
    $stmt = $conn->prepare("SELECT r.*, h.hostel_name FROM rooms r LEFT JOIN hostel h ON r.hostel_id = h.id WHERE r.id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    }
    $stmt->close();
}

// 2. THEN process form submission (room data now available)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hostel_id = (int)$_POST['hostel_id'];
    $room_number = trim($_POST['room_number']);
    $room_type = $_POST['room_type'];  // This is 'Single'
    $capacity = (int)$_POST['capacity'];
    $available_beds = (int)$_POST['available_beds'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $amenities = trim($_POST['amenities']);
    $status = $_POST['status'];
    $edit_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    // Handle image upload
    $image_path = $room['image_path'] ?? ''; // Keep existing image for edit
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_path = $upload_dir . time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    if ($edit_id > 0) {
        // UPDATE - Now compares 'Single' vs DB's '0'
        $stmt = $conn->prepare("UPDATE rooms SET hostel_id=?, room_number=?, room_type=?, capacity=?, available_beds=?, price=?, description=?, amenities=?, status=?, image_path=? WHERE id=?");
        $stmt->bind_param("issiidssssi", $hostel_id, $room_number, $room_type, $capacity, $available_beds, $price, $description, $amenities, $status, $image_path, $edit_id);

        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $message = $affected_rows > 0 ? "Room updated successfully! ($affected_rows row)" : "No changes detected";
            $action = 'list';
        } else {
            $message = 'Update failed: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        // INSERT new room
        $stmt = $conn->prepare("INSERT INTO rooms (hostel_id, room_number, room_type, capacity, available_beds, price, description, amenities, status, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issiidssss", $hostel_id, $room_number, $room_type, $capacity, $available_beds, $price, $description, $amenities, $status, $image_path);

        if ($stmt->execute()) {
            $message = 'Room added successfully!';
            $action = 'list';
        } else {
            $message = 'Add failed: ' . $stmt->error;
        }
        $stmt->close();
    }
}
// Fetch room for editing - ✅ FIXED SQL injection
$room = null;
if ($action == 'edit' && $room_id > 0) {
    $stmt = $conn->prepare("SELECT r.*, h.hostel_name FROM rooms r LEFT JOIN hostel h ON r.hostel_id = h.id WHERE r.id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch hostels for dropdown
$hostels = $conn->query("SELECT id, hostel_name FROM hostel ORDER BY hostel_name");
?>


<!DOCTYPE html>
<html>

<head>
    <title>Room Management - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* YOUR EXISTING CSS - NO CHANGES */
        .hostel-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .hostel-table th,
        .hostel-table td {
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

        select,
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        select:focus,
        input[type="number"]:focus {
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
        <h1>Room Management</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($action == 'list'): ?>
            <!-- List View -->
            <a href="?action=add" class="btn-add">+ Add New Room</a>

            <table class="hostel-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Hostel Name</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Available</th>
                        <th>Price (₹)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("
                        SELECT r.*, h.hostel_name 
                        FROM rooms r 
                        LEFT JOIN hostel h ON r.hostel_id = h.id 
                        ORDER BY h.hostel_name, r.room_number
                    ");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="hostel-image" alt="Room">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($row['hostel_name'] ?? 'Unknown Hostel'); ?></strong></td>
                                <td><strong><?php echo htmlspecialchars($row['room_number']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                                <td><?php echo $row['capacity']; ?> beds</td>
                                <td><?php echo $row['available_beds']; ?> beds</td>
                                <td>₹<?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td class="action-buttons">
                                    <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete room <?php echo htmlspecialchars($row['room_number']); ?>?')">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='10' style='text-align:center;'>No rooms found. Add your first room!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        <?php elseif ($action == 'add' || $action == 'edit'): ?>
            <!-- Add/Edit Form -->
            <a href="?action=list" class="btn-back">← Back to List</a>

            <form method="POST" enctype="multipart/form-data" class="form-container">
                <?php if ($room): ?>
                    <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Select Hostel <span style="color:red;">*</span></label>
                    <select name="hostel_id" required>
                        <option value="">-- Select Hostel --</option>
                        <?php
                        while ($hostel_row = $hostels->fetch_assoc()):
                            $selected = ($room && $room['hostel_id'] == $hostel_row['id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $hostel_row['id']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($hostel_row['hostel_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Room Number <span style="color:red;">*</span></label>
                    <input type="text" name="room_number" value="<?php echo $room ? htmlspecialchars($room['room_number']) : ''; ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Room Type *</label>
                        <select name="room_type" required>
                            <option value="">-- Select Room Type --</option>
                            <option value="Single" <?php echo ($room && $room['room_type']=='Single')?'selected':''; ?>>Single</option>
                            <option value="Double" <?php echo ($room && $room['room_type']=='Double')?'selected':''; ?>>Double</option>
                            <option value="Triple" <?php echo ($room && $room['room_type']=='Triple')?'selected':''; ?>>Triple</option>
                            <option value="Quad" <?php echo ($room && $room['room_type'] == 'Quad') ? 'selected' : ''; ?>>Quad</option>
                        </select>
                    </div>







                    <div class="form-group">
                        <label>Capacity <span style="color:red;">*</span></label>
                        <input type="number" name="capacity" value="<?php echo $room ? $room['capacity'] : ''; ?>" min="1" max="8" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Available Beds <span style="color:red;">*</span></label>
                        <input type="number" name="available_beds" value="<?php echo $room ? $room['available_beds'] : ''; ?>" min="0" max="8" required>
                    </div>

                    <div class="form-group">
                        <label>Price Per Bed (₹) <span style="color:red;">*</span></label>
                        <input type="number" name="price" step="0.01" min="0" value="<?php echo $room ? $room['price'] : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Status <span style="color:red;">*</span></label>
                        <select name="status" required>
                            <option value="Available" <?php echo ($room && $room['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Booked" <?php echo ($room && $room['status'] == 'Booked') ? 'selected' : ''; ?>>Booked</option>
                            <option value="Maintenance" <?php echo ($room && $room['status'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo $room ? htmlspecialchars($room['description']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Amenities (comma-separated)</label>
                    <input type="text" name="amenities" value="<?php echo $room ? htmlspecialchars($room['amenities']) : ''; ?>" placeholder="WiFi, AC, Parking, Laundry">
                </div>

                <div class="form-group">
                    <label>Room Image</label>
                    <input type="file" name="image" accept="image/*">
                    <?php if ($room && !empty($room['image_path'])): ?>
                        <p style="color: #666; font-size: 14px; margin-top: 5px;">
                            Current: <?php echo basename($room['image_path']); ?>
                        </p>
                        <img src="<?php echo htmlspecialchars($room['image_path']); ?>" alt="Room" style="max-width: 200px; margin-top: 10px; border-radius: 5px;">
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn"><?php echo $room ? 'Update' : 'Add'; ?> Room</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>