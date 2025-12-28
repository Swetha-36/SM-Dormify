

<div class="container">
    <div class="row">
        <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sm";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT hostel_name, description, amenities, food, price, image_path FROM hostel";
$result = $conn->query($sql);

// Check if query was successful
if ($result === false) {
    die("Error in query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
               
                <img src="<?php echo $hostel['image_path']; ?>" alt="Hostel"
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($row['hostel_name']); ?>">
                
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['hostel_name']); ?></h5>
                    <div class="room__card__details">
                        <div>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Amenities:</strong> <?php echo htmlspecialchars($row['amenities']); ?></p>
                            <p><strong>Food:</strong> <?php echo htmlspecialchars($row['food']); ?></p>
                        </div>
                        <h3 class="d-inline"><i class="bi bi-currency-rupee"></i><?php echo number_format($row['price']); ?>/month</h3>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "<p>No hostels found in the database.</p>";
}
$conn->close();
?>
    </div>
</div>

