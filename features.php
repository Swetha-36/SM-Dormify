<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features</title>
    
    <!-- MISSING: Remix Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    
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
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        /* MISSING: Body styles */
        body {
            font-family: "Poppins", sans-serif;
            background-color: #fafafa;
        }

        .section__container {
            max-width: var(--max-width);
            margin: auto;
            padding: 5rem 1rem;
        }

        .section__subheader {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section__header {
            font-size: 3rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
        }

        .feature__container :is(.section__subheader, .section__header) {
            text-align: center;
        }

        .feature__grid {
            margin-top: 4rem;
            display: grid;
            gap: 2rem;
        }

        /* ADDED: Card container styles */
        .feature__card {
            padding: 2rem 1.5rem;
            text-align: center;
            background-color: var(--white);
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* ADDED: Hover effect */
        .feature__card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .feature__card span {
            display: inline-block;
            margin-bottom: 1rem; /* CHANGED: from 0.5rem to 1rem */
            font-size: 2.5rem;
            color: var(--secondary-color);
        }

        .feature__card h4 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark); /* FIXED: was "counter-reset" (typo) */
        }

        .feature__card p {
            color: var(--text-light);
            line-height: 1.6; /* ADDED: Better readability */
            font-size: 0.95rem; /* ADDED: Consistent sizing */
        }

        /* Responsive styles */
        @media (width > 480px) {
            .feature__grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (width > 768px) {
            .feature__grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* ADDED: Mobile optimization */
        @media (width < 480px) {
            .section__header {
                font-size: 2rem;
            }

            .feature__card {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <section class="section__container feature__container" id="feature">
        <p class="section__subheader">FACILITIES</p>
        <h2 class="section__header">Core Features</h2>
        <div class="feature__grid">
            <div class="feature__card">
                <span><i class="ri-thumb-up-line"></i></span>
                <h4>Have High Rating</h4>
                <p>We curate hostels that consistently receive high ratings and positive reviews.</p>
            </div>
            <div class="feature__card">
                <span><i class="ri-time-line"></i></span>
                <h4>24/7 Security</h4>
                <p>Your safety is our top priority with round-the-clock surveillance.</p>
            </div>
            <div class="feature__card">
                <span><i class="ri-map-pin-line"></i></span>
                <h4>Best Location</h4>
                <p>Our hostels are located in the most prime and accessible areas.</p>
            </div>
            <div class="feature__card">
                <span><i class="ri-wifi-fill"></i></span>
                <h4>High-Speed Wi-Fi</h4>
                <p>Stay connected anytime, anywhere with our fast Wi-Fi network.</p>
            </div>
            <div class="feature__card">
                <span><i class="ri-hotel-bed-fill"></i></span>
                <h4>Fully Furnished Rooms</h4>
                <p>Every room includes essentials for a comfortable stay.</p>
            </div>
            <div class="feature__card">
                <span><i class="ri-restaurant-fill"></i></span>
                <h4>Hygienic Meals</h4>
                <p>Healthy and delicious meals prepared with utmost hygiene.</p>
            </div>
        </div>
    </section>
</body>
</html>
