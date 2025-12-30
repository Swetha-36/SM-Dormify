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

        .rooms-layout {
            max-width: var(--max-width);
            margin: 2rem auto;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 3rem;
            padding-inline: 1rem;
        }

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
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
        }

        .section__header {
            font-size: 3rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
            text-align: center;
        }


        .room__grid {
            max-width: 100%;
            margin-inline: 0;
            margin-top: 2rem;
            display: grid;
            gap: 1rem;
            grid-template-columns: 1fr;
        }

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

        .room__card img {
            width: 180px;
            height: 100%;
            max-height: 220px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }

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

        /* UPDATED: Smaller card images */
        .card-img-top {
    height: 280px !important;
    object-fit: cover;
    object-position: center bottom; /* Try: center bottom, center top, 50% 30%, etc. */
}


        /* NEW: Add spacing after cards section */
        .room__grid {
            margin-bottom: 3rem; /* Adds space after the three cards */
        }

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
            
            .card-img-top {
                height: 220px !important;
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
            padding-left: 6rem !important;
            padding-right: 6rem !important;
        }

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
    </style>
</head>

<body>
    <section class="room__container" id="room">
    <p class="section__subheader">Rooms</p>
        <h2 class="section__header">Hand Picked Rooms</h2>
        <div class="room__grid">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-13.jpg" class="card-img-top" alt="S-Hostels Room">
                        <div class="card-body">
                            <h5 class="card-title">S-Hostels</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A comfortable single room located in Hyderabad, suitable for students and working professionals.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>
                                    <p><strong>Food:</strong> Veg & Non-veg</p>
                                    <p><strong>Rating:</strong></p>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-4.jpg" class="card-img-top" alt="StayHub Room">
                        <div class="card-body">
                            <h5 class="card-title">StayHub</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A clean and airy double-sharing accommodation in Delhi, suitable for students and IT employees.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>
                                    <p><strong>Food:</strong> Veg & Non-veg</p>
                                    <p><strong>Rating:</strong></p>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                       
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100">
                        <img src="assets/room-9.jpg" class="card-img-top" alt="Grace Girls Hostel Room">
                        <div class="card-body">
                            <h5 class="card-title">Grace Girls Hostel</h5>
                            <div class="room__card__details">
                                <div>
                                    <p>A well-ventilated triple-sharing room located in Mumbai, ideal for a social and affordable stay.</p>
                                    <p><strong>Amenities:</strong> Wi-Fi, AC, Geyser, Washing Machine, Study Table, Locker</p>
                                    <p><strong>Food:</strong> Veg & Non-veg</p>
                                    <p><strong>Rating:</strong></p>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                        <span class="ms-2 small text-muted">(3.2/5)</span>
                                    </div>
                                </div>
                                <h3 class="d-inline"><i class="bi bi-currency-rupee"></i>1500/month</h3>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- More Options Button with proper spacing -->
    <div class="d-flex justify-content-center mb-5">
    <button class="btn btn-primary px-5 py-2" type="button" onclick="window.location.href='rooms1.php'">More Options</button>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
