<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>SM - Rooms</title>
    <style>
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
        }

        .btn-primary {
            background: var(--secondary-color);
            color: var(--white);
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
    </style>
</head>

<body>
    <p style="font-size: 2.5rem; font-weight: 700; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; text-align: center; color: #2c3e50; margin: 2rem 0; line-height: 1.2;">
        Discover Your Perfect Hostel with Smart Filters
    </p>

    <nav class="navbar navbar-expand-lg navbar-light bg-color-white">
        <div class="container-xxl px-5" style=" margin-top: 1rem; margin-bottom: 1rem;">
            <a class="navbar-brand" href="#">Rooms</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavFilters">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavFilters">
                <ul class="navbar-nav me-auto">

                    <!-- 1. Room Type Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="roomType" role="button" data-bs-toggle="dropdown">
                            Room Type
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="roomType">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="room_type" id="single"><label class="form-check-label" for="single">Single</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="room_type" id="double"><label class="form-check-label" for="double">Double</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="room_type" id="triple"><label class="form-check-label" for="triple">Triple</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="room_type" id="sharing"><label class="form-check-label" for="sharing">Common Sharing</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 2. Price Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="price" role="button" data-bs-toggle="dropdown">
                            Price
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="price">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p1"><label class="form-check-label" for="p1">₹3,000–₹5,000</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p2"><label class="form-check-label" for="p2">₹5,000–₹8,000</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p3"><label class="form-check-label" for="p3">₹8,000–₹12,000</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="price_range" id="p4"><label class="form-check-label" for="p4">₹12,000–₹15,000</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 3. Gender Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="gender" role="button" data-bs-toggle="dropdown">
                            Gender
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="gender">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="gender" id="male"><label class="form-check-label" for="male">Male</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="gender" id="female"><label class="form-check-label" for="female">Female</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 4. Rating Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="rating" role="button" data-bs-toggle="dropdown">
                            Rating
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="rating">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r1"><label class="form-check-label" for="r1">1+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r2"><label class="form-check-label" for="r2">2+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r3"><label class="form-check-label" for="r3">3+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r4"><label class="form-check-label" for="r4">4+ stars</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="rating" id="r5"><label class="form-check-label" for="r5">5 stars only</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 5. Occupancy Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="occupancy" role="button" data-bs-toggle="dropdown">
                            Occupancy
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="occupancy">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="occupancy_type" id="student"><label class="form-check-label" for="student">Student</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="occupancy_type" id="working"><label class="form-check-label" for="working">Working</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="occupancy_type" id="both"><label class="form-check-label" for="both">Both</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 6. Furnishing Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="furnishing" role="button" data-bs-toggle="dropdown">
                            Furnishing
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="furnishing">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="furnishing" id="fully"><label class="form-check-label" for="fully">Fully furnished</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="furnishing" id="semi"><label class="form-check-label" for="semi">Semi-furnished</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="furnishing" id="unfurnished"><label class="form-check-label" for="unfurnished">Unfurnished</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 7. Food Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="food" role="button" data-bs-toggle="dropdown">
                            Food
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="food">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="food" id="veg"><label class="form-check-label" for="veg">Veg</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="food" id="nonveg"><label class="form-check-label" for="nonveg">Non-veg</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="food" id="both_food"><label class="form-check-label" for="both_food">Both</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- 8. Amenities Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="amenities" role="button" data-bs-toggle="dropdown">
                            Amenities
                        </a>
                        <ul class="dropdown-menu filter-dropdown" aria-labelledby="amenities">
                            <li>
                                <div class="px-3 py-2">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="wifi" id="wifi"><label class="form-check-label" for="wifi">Wi-Fi</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="ac" id="ac"><label class="form-check-label" for="ac">AC / Non-AC</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="geyser" id="geyser"><label class="form-check-label" for="geyser">Geyser</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="washing-machine" id="washing"><label class="form-check-label" for="washing">Washing Machine</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="study-table" id="study"><label class="form-check-label" for="study">Study Table</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="amenities" value="cupboard-locker" id="cupboard"><label class="form-check-label" for="cupboard">Cupboard/Locker</label></div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Action Buttons -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0" align-items="right">
                        <li class="nav-item">
                            <button class="btn btn-primary btn-sm me-md-2">Reset</button>
                        </li> 
                        <li class="nav-item">
                            <button class="btn btn-primary btn-sm">Apply Filters</button>
                        </li>
                    </ul>

                    

                </ul>
            </div>
        </div>
    </nav>

    <section class="room__container" id="room">
        <p class="section__subheader">ROOMS</p>
        <h2 class="section__header">Hand Picked Rooms</h2>
        <div class="room__grid">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_path']); ?>" 
             class="img-fluid mb-3" 
             alt="<?php echo htmlspecialchars($row['hostel_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title">S-Hostels</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A comfortable single room located in Hyderabad, suitable for students and working professionals.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                    </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>


                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-4.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">StayHub</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A clean and airy double-sharing accommodation in Delhi, suitable for students and IT employees.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-9.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Grace Girls Hostel</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A well-ventilated triple-sharing room located in Mumbai, ideal for a social and affordable stay.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                    </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-10.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Metro Boys Hostel</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A spacious common-sharing hostel located in Jaipur, offering a lively and friendly living space.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-2.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Urban Co-Living</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A comfortable single room in Pune, offering privacy and a calm living environment</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>

                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-5.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Serene Stay Girls Hostel</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A spacious double-sharing room located in Hyderabad, designed for comfort and convenience.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                    </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-8.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">LiveEasy Co-Living</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A comfortable triple-sharing accommodation in Kochi, perfect for students seeking community living.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-11.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">MyCoStay</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A well-organized common-sharing accommodation in Noida, ideal for students and young professionals.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-3.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Abode Hostel</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A well-maintained single-sharing accommodation situated in Chennai, ideal for focused living.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-6.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Zivo</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A budget-friendly double-sharing room in Coimbatore, offering a friendly and homely stay.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-7.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Arcade Hostels</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A neatly maintained triple-sharing room in Indore, combining comfort with affordability.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-12.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Livin</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A clean and comfortable common-sharing room situated in Vijayawada, promoting a community-driven stay.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>

                                    <!-- Food type -->
                                    <p><strong>Food:</strong> Veg & Non-veg</p>

                                    <!-- Rating (simple stars) -->
                                    <p><strong>Rating:</strong>
                                        <!-- 3.2/5 rating (3 full + 2 empty stars) -->
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>

                                    </p>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-2">
                            <a href="payment.php" class="btn btn-primary btn-sm w-100">Book Now</a>
                        </div>


                    </div>
                </div>
            </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>