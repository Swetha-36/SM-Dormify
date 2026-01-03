<?php 
session_start();
$is_logged_in = isset($_SESSION['reg_id']) && $_SESSION['reg_id'] > 0;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start building SQL query
$sql = "SELECT id, hostel_name, description, amenities, food, price, image_path, occupancy_type, room_type, hostel_type FROM hostel WHERE 1=1";

$conditions = [];

// 1. Filter by Room Type (multiple)
if (isset($_POST['room_type']) && !empty($_POST['room_type'])) {
    $room_types = $_POST['room_type'];
    $conditions[] = "(room_type LIKE '%" . implode("%' OR room_type LIKE '%", $room_types) . "%')";
}

// 2. Filter by Price Range (multiple)
if (isset($_POST['price_range']) && !empty($_POST['price_range'])) {
    $price_conditions = [];
    foreach ($_POST['price_range'] as $range) {
        switch($range) {
            case 'p1':
                $price_conditions[] = "(price BETWEEN 3000 AND 5000)";
                break;
            case 'p2':
                $price_conditions[] = "(price BETWEEN 5000 AND 8000)";
                break;
            case 'p3':
                $price_conditions[] = "(price BETWEEN 8000 AND 12000)";
                break;
            case 'p4':
                $price_conditions[] = "(price BETWEEN 12000 AND 15000)";
                break;
        }
    }
    if (!empty($price_conditions)) {
        $conditions[] = "(" . implode(" OR ", $price_conditions) . ")";
    }
}

// 5. Filter by Occupancy
if (isset($_POST['occupancy']) && !empty($_POST['occupancy'])) {
    $occupancy = $conn->real_escape_string($_POST['occupancy']);
    if ($occupancy != 'Both') {
        $conditions[] = "(occupancy_type = '$occupancy' OR occupancy_type = 'Both')";
    }
}

// 7. Filter by Food
if (isset($_POST['food']) && !empty($_POST['food'])) {
    $food = $conn->real_escape_string($_POST['food']);
    if ($food != 'Both') {
        $conditions[] = "(food = '$food' OR food = 'Both')";
    }
}

// 8. Filter by Amenities (multiple - all must be present)
if (isset($_POST['amenities']) && !empty($_POST['amenities'])) {
    foreach ($_POST['amenities'] as $amenity) {
        $conditions[] = "amenities LIKE '%" . $conn->real_escape_string($amenity) . "%'";
    }
}

// Add conditions to SQL
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY price ASC";

$result = $conn->query($sql);

// Generate ONLY the cards HTML (no <html>, <head>, <body>, or navbar)
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="col">
            <div class="card h-100">
                <img src="/sm/admin/<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['hostel_name']); ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['hostel_name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="card-text"><strong>Amenities:</strong> <?php echo htmlspecialchars($row['amenities']); ?></p>
                    <p class="card-text"><strong>Food:</strong> <?php echo htmlspecialchars($row['food']); ?></p>
                    <p class="card-text"><strong>Occupancy:</strong> <?php echo htmlspecialchars($row['occupancy_type']); ?></p>
                    <p class="card-text"><strong>Room Type:</strong> <?php echo htmlspecialchars($row['room_type']); ?></p>
                    
                    <div class="mt-auto">
                        <h3 class="price-tag mb-3">
                            <i class="bi bi-currency-rupee"></i><?php echo number_format($row['price']); ?>/month
                        </h3>
                        <button type="button" 
        class="btn btn-primary w-100" 
        onclick="checkLoginAndBook(<?php echo $row['id']; ?>, <?php echo $row['price']; ?>, <?php echo $is_logged_in ? 'true' : 'false'; ?>)">
    Book Now
</button>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="col-12">
            <div class="alert alert-warning text-center">
                <h5>No hostels found matching your criteria</h5>
                <p>Try adjusting your filters or <button class="btn btn-link p-0" onclick="resetFilters()">view all hostels</button></p>
            </div>
          </div>';
}

$conn->close();
?>
