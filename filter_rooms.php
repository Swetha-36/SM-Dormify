<?php
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
$sql = "SELECT hostel_name, description, amenities, food, price, image_path, gender, rating, occupancy_type, furnishing, room_type FROM hostel WHERE 1=1";

$conditions = [];
$params = [];
$types = '';

// 1. Filter by Room Type (multiple)
if (isset($_POST['room_type']) && !empty($_POST['room_type'])) {
    $room_types = $_POST['room_type'];
    $placeholders = array_fill(0, count($room_types), '?');
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

// 3. Filter by Gender
if (isset($_POST['gender']) && !empty($_POST['gender'])) {
    $conditions[] = "gender = '" . $conn->real_escape_string($_POST['gender']) . "'";
}

// 4. Filter by Rating
if (isset($_POST['rating']) && !empty($_POST['rating'])) {
    $rating_value = (int)$_POST['rating'];
    $conditions[] = "rating >= $rating_value";
}

// 5. Filter by Occupancy
if (isset($_POST['occupancy']) && !empty($_POST['occupancy'])) {
    $occupancy = $conn->real_escape_string($_POST['occupancy']);
    if ($occupancy == 'Both') {
        // Don't add condition for "Both"
    } else {
        $conditions[] = "(occupancy_type = '$occupancy' OR occupancy_type = 'Both')";
    }
}

// 6. Filter by Furnishing (multiple)
if (isset($_POST['furnishing']) && !empty($_POST['furnishing'])) {
    $furnishing_items = $_POST['furnishing'];
    $furnishing_conditions = [];
    foreach ($furnishing_items as $item) {
        $furnishing_conditions[] = "furnishing LIKE '%" . $conn->real_escape_string($item) . "%'";
    }
    $conditions[] = "(" . implode(" OR ", $furnishing_conditions) . ")";
}

// 7. Filter by Food
if (isset($_POST['food']) && !empty($_POST['food'])) {
    $food = $conn->real_escape_string($_POST['food']);
    if ($food == 'Both') {
        // Show all
    } else {
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

// Generate HTML output
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
                    
                    <p class="card-text"><strong>Rating:</strong></p>
                    <div class="d-flex align-items-center mb-3">
                        <?php
                        $rating = isset($row['rating']) ? (int)$row['rating'] : 3;
                        for($i = 1; $i <= 5; $i++) {
                            if($i <= $rating) {
                                echo '<i class="bi bi-star-fill text-warning"></i>';
                            } else {
                                echo '<i class="bi bi-star text-warning"></i>';
                            }
                        }
                        ?>
                        <span class="ms-2 small text-muted">(<?php echo $rating; ?>/5)</span>
                    </div>
                    
                    <div class="mt-auto">
                        <h3 class="price-tag mb-3">
                            <i class="bi bi-currency-rupee"></i><?php echo number_format($row['price']); ?>/month
                        </h3>
                        <a href="payment.php class="btn btn-primary w-100">Book Now</a>
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
