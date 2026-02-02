<?php
session_start();
$is_logged_in = isset($_SESSION['reg_id']) && $_SESSION['reg_id'] > 0;

// ✅ SAFE: Capture initial filters from book.php - NO ARRAYS IN STRINGS
$initial_filters = [
    'room_type' => isset($_GET['room_type']) ? trim($_GET['room_type']) : '',
    'price_range' => isset($_GET['price_range']) ? trim($_GET['price_range']) : '',
    'location' => isset($_GET['location']) ? trim($_GET['location']) : ''
];

// ✅ SAFE DEBUG - Only scalars, no arrays in echo
?>
<!-- DEBUG INFO (Safe - no arrays) -->
<script>
console.log('🔍 PHP $_GET raw:', <?php echo json_encode($_GET); ?>);
console.log('🔍 Parsed filters:', <?php echo json_encode($initial_filters); ?>);
console.log('🔍 Full URL:', <?php echo json_encode($_SERVER['REQUEST_URI']); ?>);
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SM-Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        /* Your existing CSS styles */
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
        .badge-filter {
            background-color: #f6ac0f;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin: 0.25rem;
            display: inline-block;
        }
        .active-filters {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <p style="font-size: 2.5rem; font-weight: 700; text-align: center; margin: 2rem 0;">
        Discover Your Perfect Hostel with Smart Filters
    </p>

    <!-- Display Active Initial Filters -->
    <?php if (!empty(array_filter($initial_filters))): ?>
    <div class="container active-filters">
        <strong>Active Search:</strong>
        <?php if ($initial_filters['room_type']): ?>
            <span class="badge-filter">Room: <?php echo htmlspecialchars($initial_filters['room_type']); ?></span>
        <?php endif; ?>
        <?php if ($initial_filters['price_range']): ?>
            <span class="badge-filter">Max Price: ₹<?php echo number_format($initial_filters['price_range']); ?></span>
        <?php endif; ?>
        <?php if ($initial_filters['location']): ?>
            <span class="badge-filter">Location: <?php echo htmlspecialchars($initial_filters['location']); ?></span>
        <?php endif; ?>
        <a href="hostel1.php" class="btn btn-sm btn-outline-secondary ms-2">Clear Search</a>
    </div>
    <?php endif; ?>

    <!-- Secondary Filters Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-xxl px-5" style="margin-top: 1rem; margin-bottom: 1rem;">
            <a class="navbar-brand" href="#">Hostels</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavFilters">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavFilters">
                <ul class="navbar-nav me-auto">

                    <!-- Price Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="price" role="button" data-bs-toggle="dropdown">
                            Price
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="price">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p1" value="3000-5000"><label class="form-check-label" for="p1">₹3,000–₹5,000</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p2" value="5000-8000"><label class="form-check-label" for="p2">₹5,000–₹8,000</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p3" value="8000-12000"><label class="form-check-label" for="p3">₹8,000–₹12,000</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p4" value="12000-15000"><label class="form-check-label" for="p4">₹12,000–₹15,000</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Gender Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="gender" role="button" data-bs-toggle="dropdown">
                            Gender
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="gender">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="gender" id="male" value="Male"><label class="form-check-label" for="male">Male</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="gender" id="female" value="Female"><label class="form-check-label" for="female">Female</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Rating Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="rating" role="button" data-bs-toggle="dropdown">
                            Rating
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="rating">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r1" value="1"><label class="form-check-label" for="r1">1+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r2" value="2"><label class="form-check-label" for="r2">2+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r3" value="3"><label class="form-check-label" for="r3">3+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r4" value="4"><label class="form-check-label" for="r4">4+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r5" value="5"><label class="form-check-label" for="r5">5 stars only</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Occupancy Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="occupancy" role="button" data-bs-toggle="dropdown">
                            Occupancy
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="occupancy">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="occupancy_type" id="student" value="Student"><label class="form-check-label" for="student">Student</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="occupancy_type" id="working" value="Working"><label class="form-check-label" for="working">Working</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="occupancy_type" id="both" value="Both"><label class="form-check-label" for="both">Both</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Food Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="food" role="button" data-bs-toggle="dropdown">
                            Food
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="food">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="food" id="veg" value="Veg"><label class="form-check-label" for="veg">Veg</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="food" id="nonveg" value="Non-veg"><label class="form-check-label" for="nonveg">Non-veg</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="food" id="both_food" value="Both"><label class="form-check-label" for="both_food">Both</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Amenities Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="amenities" role="button" data-bs-toggle="dropdown">
                            Amenities
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="amenities">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="Wi-Fi" id="wifi"><label class="form-check-label" for="wifi">Wi-Fi</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="AC" id="ac"><label class="form-check-label" for="ac">AC / Non-AC</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="Geyser" id="geyser"><label class="form-check-label" for="geyser">Geyser</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="Washing Machine" id="washing"><label class="form-check-label" for="washing">Washing Machine</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="Study Table" id="study"><label class="form-check-label" for="study">Study Table</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="Cupboard" id="cupboard"><label class="form-check-label" for="cupboard">Cupboard/Locker</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- Action Buttons -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <button class="btn btn-primary btn-sm me-md-2" onclick="resetFilters()">Reset</button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-primary btn-sm" onclick="applyFilters()">Apply Filters</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Rooms Display Section -->
    <section class="room__container" id="room">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3" id="rooms-container">
                <!-- Results will be loaded here via AJAX -->
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ✅ SINGLE SOURCE OF TRUTH: PHP passes filters directly to JS
    const initialFilters = <?php echo json_encode($initial_filters); ?>;
    
    console.log('🔍 PHP PASSED filters:', initialFilters);

    function getFilterValues() {
        const filters = {
            // Initial filters from book.php (via PHP)
            initial_room_type: initialFilters.room_type || '',
            initial_price_range: initialFilters.price_range || '',
            initial_location: initialFilters.location || '',
            
            // Secondary filters from hostel1.php form
            gender: $('input[name="gender"]:checked').val() || '',
            rating: $('input[name="rating"]:checked').val() || '',
            food: $('input[name="food"]:checked').val() || '',
            occupancy: $('input[name="occupancy_type"]:checked').val() || '',
            amenities: []
        };

        // Collect amenities checkboxes
        $('input[name="amenities"]:checked').each(function() {
            filters.amenities.push($(this).val());
        });

        console.log('📤 Sending to filter_rooms.php:', filters);
        return filters;
    }

    function applyFilters() {
        const filters = getFilterValues();
        
        $('#rooms-container').html(`
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Searching hostels matching: ${initialFilters.room_type || 'All types'}</p>
            </div>
        `);

        $.ajax({
            url: 'filter_rooms.php',
            type: 'POST',
            data: filters,
            success: function(response) {
                console.log('✅ Success - Response length:', response.length);
                $('#rooms-container').html(response);
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX Error:', error, xhr.responseText);
                $('#rooms-container').html(`
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <h5>Error loading results</h5>
                            <p>${error}</p>
                            <details>${xhr.responseText}</details>
                        </div>
                    </div>
                `);
            }
        });
    }

    function resetFilters() {
        // Reset only secondary filters (keep book.php filters)
        $('input[type="checkbox"]').prop('checked', false);
        $('input[type="radio"]').prop('checked', false);
        applyFilters();
    }

    // ✅ SINGLE document.ready - no duplicates
    $(document).ready(function() {
        console.log('🚀 Page ready - Initial filters:', initialFilters);
        setTimeout(applyFilters, 200); // Small delay for DOM
    });

    // Login check function
    function checkLoginAndBook(hostelId, price, isLoggedIn) {
        if (!isLoggedIn) {
            Swal.fire({
                icon: 'warning',
                title: 'Please Login First',
                text: 'You need to login before booking a hostel',
                confirmButtonText: 'Go to Login',
                confirmButtonColor: '#3498db',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    sessionStorage.setItem('pendingBooking', JSON.stringify({
                        hostelId: hostelId,
                        price: price
                    }));
                    window.location.href = 'index.php?openLogin=true&redirect=rooms1';
                }
            });
        } else {
            window.location.href = 'payment.php?hostel_id=' + hostelId + '&price=' + price;
        }
    }
</script>


</body>
</html>
