<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$is_logged_in = isset($_SESSION['reg_id']) && $_SESSION['reg_id'] > 0;

// Get hostel_id from URL
$hostel_id = isset($_GET['hostel_id']) ? (int)$_GET['hostel_id'] : 0;
$hostel_name = 'Unknown Hostel'; // ✅ DEFAULT VALUE
$rooms_result = false; // ✅ DEFAULT VALUE

// Fetch hostel details FIRST
if ($hostel_id > 0) {
    $stmt = $conn->prepare("SELECT hostel_name FROM hostel WHERE id = ?");
    $stmt->bind_param("i", $hostel_id);
    $stmt->execute();
    $hostel_result = $stmt->get_result();
    if ($hostel_row = $hostel_result->fetch_assoc()) {
        $hostel_name = $hostel_row['hostel_name'];
    }
    $stmt->close();
}

// Fetch ROOMS for this specific hostel
// Fetch ROOMS for this specific hostel - FIXED VERSION
$rooms_result = false;
if ($hostel_id > 0) {
    $sql = "SELECT r.id, r.room_number, r.room_type, r.price, r.image_path, r.available_beds, r.capacity, r.description, h.hostel_name 
            FROM rooms r 
            LEFT JOIN hostel h ON r.hostel_id = h.id 
            WHERE r.hostel_id = ? AND r.status = 'Available' 
            ORDER BY r.room_number";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $hostel_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result) {
                $rooms_result = $result;  // Only set if successful
            }
        }
        $stmt->close();
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - <?php echo htmlspecialchars($hostel_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Keep ALL your existing CSS styles exactly the same -->
    <style>
        /* YOUR COMPLETE EXISTING CSS - NO CHANGES */
        @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap");
        :root {
            --primary-color: #0f1a2c;
            --secondary-color: #f6ac0f;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --extra-light: #f8fafc;
            --white: #ffffff;
            --max-width: 1200px;
            --header-font: "Playfair Display", serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            background-color: #f9f9f9;
        }

        img {
            display: block;
        }

        /* LAYOUT: FILTERS LEFT, ROOMS RIGHT */
        .rooms-layout {
            max-width: var(--max-width);
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 2rem;
            padding-inline: 1rem;
        }

        /* FILTER SIDEBAR */
        .filters {
            background: var(--white);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            height: fit-content;
        }

        .filters h3 {
            font-family: var(--header-font);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .filter-group {
            margin-bottom: 1.5rem;
        }

        .filter-group h4 {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .filter-group label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.25rem;
            cursor: pointer;
        }

        .filter-group input[type="checkbox"] {
            margin-right: 0.4rem;
        }

        .filter-group input[type="range"] {
            width: 100%;
        }

        .filter-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            border: none;
            border-radius: 4px;
            padding: 0.5rem 0.9rem;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.4s ease-in-out;
            /* Smooth color transition */
        }

        .btn-primary {
            background: #f6ac0f;
            /* Yellow shade */
            color: #ffffff;
            border: 1px solid #f6ac0f;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            /* Blue shade on hover */
            border-color: #2563eb;
            color: #ffffff;
            transform: translateY(-2px);
            /* Optional: slight lift effect */
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            /* Optional: blue shadow */
        }

        .btn-outline {
            background: transparent;
            color: var(--text-dark);
            border: 1px solid #e2e8f0;
        }


        /* ROOMS SECTION (RIGHT) */
        .room__container {
            padding-block: 0;
            padding-inline: 0;
        }

        .room__container :is(.section__subheader, .section__header) {
            padding-inline: 0;
            text-align: left;
        }

        .section__subheader {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section__header {
            font-size: 2.2rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        /* ROOMS LIST – ONE BELOW ANOTHER */
        .room__grid {
            max-width: 100%;
            margin-inline: 0;
            margin-top: 2rem;
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }

        /* ROOM CARD: IMAGE LEFT, DETAILS RIGHT */
        .room__card {
            display: flex;
            align-items: stretch;
            gap: 1rem;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
            padding: 0.75rem;
            overflow: hidden;
        }

        /* Image: fixed width, full vertical fit inside card */
        .room__card img {
            width: 180px;
            /* horizontal size */
            height: 100%;
            /* stretch vertically inside card */
            max-height: 220px;
            /* control max vertical height */
            object-fit: cover;
            /* fill & crop nicely */
            border-radius: 8px;
            flex-shrink: 0;
        }

        /* Details on the right */
        .room__card__details {
            flex: 1;
            margin-inline: 0;
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.5rem;
            background-color: transparent;
            transform: none;
            border-radius: 0;
            box-shadow: none;
            position: static;
            z-index: 1;
        }

        .room__card__details h4 {
            margin-bottom: 0.25rem;
            font-size: 1.1rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
        }

        .room__card__details p {
            color: var(--text-light);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .room__card__details h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary-color);
            white-space: nowrap;
            align-self: flex-end;
        }

        .room__card__details h3 span {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* RESPONSIVE: STACK FILTERS ABOVE ROOMS ON SMALL SCREENS */
        @media (max-width: 768px) {
            .rooms-layout {
                grid-template-columns: 1fr;
            }

            .room__card {
                flex-direction: column;
            }

            .room__card img {
                width: 100%;
                height: 220px;
                max-height: 220px;
            }

            .room__card__details h3 {
                align-self: flex-start;
            }
        }

        @media (max-width: 480px) {
            .section__header {
                font-size: 2rem;
            }
        }

        .filter-dropdown {
            min-width: 220px;
        }

        .wide-navbar-container {
            max-width: 1800px !important;
            /* Super wide */
            padding-left: 6rem !important;
            padding-right: 6rem !important;
        }

        /* Responsive scaling */
        @media (max-width: 1600px) {
            .wide-navbar-container {
                padding-left: 4rem;
                padding-right: 4rem;
            }
        }

        @media (max-width: 1200px) {
            .wide-navbar-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        .card-img-top {
            height: 550px !important;
            /* Perfect for 3-column layout */
            object-fit: cover;
            /* Sharp crop, no stretch */
            object-position: center top;
            /* Focus on room top */
        }
    
        
        /* Add this small addition for rooms */
        .hostel-header {
            background: linear-gradient(135deg, #0f1a2c 0%, #1e3a5f 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }
        .back-btn {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Hostel Header -->
    <div class="hostel-header">
        <a href="javascript:history.back()" class="btn back-btn">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin: 0;">
            Rooms in <strong><?php echo htmlspecialchars($hostel_name); ?></strong>
        </h1>
        <p style="font-size: 1.1rem; opacity: 0.9;">Choose your perfect room</p>
    </div>

    <section class="room__container" id="room">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="rooms-container">
            <?php
    if ($rooms_result !== false && $rooms_result->num_rows > 0) {
        while ($room = $rooms_result->fetch_assoc()) {
    ?>
            <div class="col">
    <div class="card h-100">
        <img src="/sm/admin/<?php echo htmlspecialchars($room['image_path'] ?? 'uploads/no-image.jpg'); ?>" 
             class="card-img-top" 
             alt="<?php echo htmlspecialchars($room['room_number']); ?>"
             onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
        
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($room['room_type']); ?> Room</h5>
            <p class="card-text"><?php echo htmlspecialchars($room['description']); ?> Room</p>
            <p class="card-text"><strong>Capacity:</strong> <?php echo $room['capacity']; ?> beds</p>
            <p class="card-text"><strong>Available:</strong> <?php echo $room['available_beds']; ?>/<?php echo $room['capacity']; ?></p>

            <div class="mt-auto">
                <h3 class="price-tag mb-3">
                    <i class="bi bi-currency-rupee"></i><?php echo number_format($room['price']); ?>/bed/month
                </h3>
                
                <!-- UPDATED BUTTON - SAME FORMAT -->
                <button type="button"
        class="btn btn-primary w-100"
        onclick="bookRoom(<?php echo $room['id']; ?>, '<?php echo addslashes($room['room_number']); ?>', <?php echo $room['price']; ?>, '<?php echo addslashes($hostel_name); ?>')">
    Book Room
</button>

                

            </div>
        </div>
    </div>
</div>

            <?php
                }
            } else {
                echo '<div class="col-12"><div class="text-center py-5"><i class="bi bi-inbox display-1 text-muted mb-4"></i><h4>No Rooms Available</h4><p class="text-muted">No available rooms found for this hostel. Please check back later.</p></div></div>';
            }
            
            ?>











        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    function loginFirst(roomId) {
        Swal.fire({
            icon: 'info',
            title: 'Login Required',
            text: 'Please login to book a room',
            confirmButtonText: 'Go to Login'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php';
            }
        });
    }
    
   let currentSwalInstance = null;
let basePriceGlobal = 0;

function bookRoom(roomId, roomNumber, price, hostelName) {
    basePriceGlobal = price; // Store globally
    
    Swal.fire({
        title: 'Book Room Confirmation',
        html: `
            <style>
                .booking-summary { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
                .amenity-item { display: flex; align-items: center; margin-bottom: 12px; font-size: 14px; padding: 8px; border-radius: 6px; cursor: pointer; transition: background 0.2s; }
                .amenity-item:hover { background: #e9ecef; }
                .amenity-checkbox { margin-right: 12px; width: 18px; height: 18px; }
                .amenity-price { margin-left: auto; color: #28a745; font-weight: 600; }
                .total-section { font-size: 18px; font-weight: bold; margin-top: 20px; padding-top: 15px; border-top: 2px solid #dee2e6; }
                .total-highlight { color: #28a745; font-size: 22px; }
            </style>
            <div class="booking-summary">
                <h6><strong>${hostelName}</strong></h6>
                <p><strong>Room:</strong> ${roomNumber}</p>
                <p><strong>Base Price:</strong> ₹${price.toLocaleString()}/bed/month</p>
            </div>
            
            <h6>Select Amenities (Optional):</h6>
            <div id="amenities-list">
                <!-- Amenities will be populated by JS -->
            </div>
            
            <div class="total-section">
                <div>Base Room: ₹${price.toLocaleString()}</div>
                <div id="amenities-total" style="color: #6c757d;">Amenities: ₹0</div>
                <div class="total-highlight">
                    <strong>Total: ₹<span id="total-price">${price.toLocaleString()}</span></strong>
                </div>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Proceed to Payment',
        cancelButtonText: 'Cancel',
        width: '600px',
        didOpen: () => {
            currentSwalInstance = Swal.getPopup();
            populateAmenities();
        },
        preConfirm: () => {
            const totalPrice = parseInt(document.getElementById('total-price').textContent.replace(/[^\d]/g, ''));
            return {
                roomId, roomNumber, basePrice: price, totalPrice, 
                amenities: JSON.stringify(selectedAmenities)
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const data = result.value;
            const hostelId = <?php echo $hostel_id; ?>; // PHP variable
            window.location.href = `payment.php?room_id=${data.roomId}&room_number=${encodeURIComponent(data.roomNumber)}&price=${data.totalPrice}&hostel_id=${hostelId}&amenities=${data.amenities}`;
        }
        // Reset
        selectedAmenities = {};
    });
}

const amenities = {
    'ac': { name: 'Air Conditioning', price: 1000 },
    'wifi': { name: 'High-Speed WiFi', price: 500 },
    'laundry': { name: 'Weekly Laundry', price: 800 },
    'meals': { name: '3 Meals/Day', price: 3000 },
    'gym': { name: 'Gym Access', price: 1200 },
    'cleaning': { name: 'Daily Cleaning', price: 600 },
    'parking': { name: 'Parking', price: 700 }
};

let selectedAmenities = {};

function populateAmenities() {
    const container = currentSwalInstance.querySelector('#amenities-list');
    container.innerHTML = Object.entries(amenities).map(([key, amenity]) => `
        <div class="amenity-item" onclick="toggleAmenity('${key}')">
            <input type="checkbox" class="amenity-checkbox" id="amenity-${key}" onchange="handleAmenityChange('${key}', this.checked)">
            <label for="amenity-${key}" style="margin: 0; cursor: pointer; flex: 1;">${amenity.name}</label>
            <span class="amenity-price">+₹${amenity.price.toLocaleString()}</span>
        </div>
    `).join('');
}

function toggleAmenity(key) {
    const checkbox = currentSwalInstance.querySelector(`#amenity-${key}`);
    checkbox.checked = !checkbox.checked;
    handleAmenityChange(key, checkbox.checked);
}

function handleAmenityChange(key, isChecked) {
    if (isChecked) {
        selectedAmenities[key] = true;
    } else {
        delete selectedAmenities[key];
    }
    updateTotal();
}

function updateTotal() {
    const amenitiesTotal = Object.keys(selectedAmenities).reduce((sum, key) => {
        return selectedAmenities[key] ? sum + (amenities[key]?.price || 0) : sum;
    }, 0);
    
    const total = basePriceGlobal + amenitiesTotal;
    
    currentSwalInstance.querySelector('#amenities-total').textContent = `Amenities: ₹${amenitiesTotal.toLocaleString()}`;
    currentSwalInstance.querySelector('#total-price').textContent = total.toLocaleString();
}
    </script> 

  
</body>
</html>
