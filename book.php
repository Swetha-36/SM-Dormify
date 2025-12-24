<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Booking Section</title>
    
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap");

        /* Define CSS Variables FIRST */
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
        }

        .section__container {
            max-width: var(--max-width);
            margin: auto;
            padding: 5rem 1rem;
        }

        .booking {
            background-color: var(--primary-color);
        }

        .booking__container form {
            max-width: 350px;
            margin: auto;
            display: grid;
            gap: 2rem;
        }

        .input__group {
            display: grid;
            gap: 1rem;
        }

        .input__group label {
            font-weight: 600;
            color: var(--white);
        }

        .input__group input,
        .input__group select {
            width: 100%;
            font-size: 1rem;
            padding: 0.5rem;
            color: var(--white);
            background-color: transparent;
            outline: none;
            border: none;
            border-bottom: 1px solid var(--text-light);
            border-radius: 5px;
        }

        .input__group input::placeholder {
            color: var(--text-light);
        }

        /* Style for Bootstrap select */
        .input__group select.form-select {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border: 1px solid var(--text-light);
        }

        .input__group select.form-select option {
            background-color: var(--primary-color);
            color: var(--white);
        }

        /* Button styles - INCREASED SPECIFICITY TO OVERRIDE BOOTSTRAP */
        .booking .btn,
        button.btn.search-btn {
            padding: 0.75rem 2rem !important;
            font-size: 1rem !important;
            color: var(--white) !important;
            background-color: var(--secondary-color) !important;
            border: none !important;
            border-radius: 5px !important;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600 !important;
            width: 100%;
        }

        .booking .btn:hover,
        button.btn.search-btn:hover {
            background-color: #d89a0d !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
            color: var(--white) !important;
        }

        /* Responsive styles */
        @media (width > 768px) {
            .booking__container form {
                max-width: 100%;
                grid-template-columns: repeat(4, 1fr);
                align-items: end;
            }
        }
    </style>
</head>
<body>
    <section class="booking">
        <div class="section__container booking__container">
            <form action="available_rooms.php" method="POST">
                <!-- Room Type -->
                <div class="input__group">
                    <label for="roomType" class="form-label fw-semibold">
                        Room Type
                    </label>
                    <select id="roomType" name="roomType" class="form-select shadow-none" required>
                        <option value="" disabled selected>Select room type</option>
                        <option value="single">Single Room</option>
                        <option value="double">Double Sharing</option>
                        <option value="common">Common Sharing</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div class="input__group">
                    <label for="price" class="form-label fw-semibold">
                        Price Range
                    </label>
                    <select id="price" name="price" class="form-select shadow-none" required>
                        <option value="" disabled selected>Select price range</option>
                        <option value="below5k">Below ₹5,000</option>
                        <option value="5kto10k">₹5,000 - ₹10,000</option>
                        <option value="10kto15k">₹10,000 - ₹15,000</option>
                        <option value="above15k">Above ₹15,000</option>
                    </select>
                </div>

                <!-- Location -->
                <div class="input__group">
                    <label for="location">Location</label>
                    <input id="location" name="location" type="text" placeholder="Stay location" required />
                </div>

                <!-- Submit Button -->
                <div class="input__group">
                    <button type="submit" class="btn search-btn">Search</button>
                </div>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
