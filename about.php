<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap");

        /* ========== CSS VARIABLES (MISSING IN YOUR CODE) ========== */
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

        /* ========== RESET STYLES ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
        }

        img {
            width: 100%;
            display: block;
        }

        /* ========== ABOUT SECTION ========== */
        .about {
            position: relative;
            isolation: isolate;
            background-color: var(--white);
        }

        /* Background overlay effect */
        .about::before {
            position: absolute;
            content: "";
            bottom: 0;
            right: 0;
            height: 75%;
            width: 100%;
            background-image: url("assets/about-bg.jpg");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            opacity: 0.05;
            z-index: -1;
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
        }

        .section__header {
            font-size: 3rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
        }

        .section__description {
            margin-top: 1rem;
            color: var(--text-light);
            line-height: 1.6;
        }

        .about__container {
            display: grid;
            gap: 4rem 2rem;
        }

        .about__grid {
            display: grid;
            gap: 1rem;
        }

        .about__card {
            height: 100%;
            padding: 2rem;
            display: grid;
            place-content: center;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .about__card:hover {
            transform: translateY(-5px);
        }

        .about__card span {
            margin-bottom: 1rem;
            font-size: 3rem;
            color: var(--secondary-color);
        }

        .about__card h4 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
        }

        .about__card p {
            color: var(--text-light);
            line-height: 1.6;
        }

        .about__card:nth-child(4) {
            background-color: var(--primary-color);
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .about__card:nth-child(4) :is(h4, p) {
            color: var(--white);
        }

        .about__image {
            overflow: hidden;
            border-radius: 8px;
        }

        .about__image img {
            height: 100%;
            min-height: 300px;
            object-fit: cover;
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .about__image:hover img {
            transform: scale(1.05);
        }

        .about__content .section__description {
            margin-bottom: 2rem;
        }

        /* ========== RESPONSIVE DESIGN ========== */
        
        /* Tablet (480px and up) */
        @media (width > 480px) {
            .about__grid {
                grid-template-columns: repeat(2, 1fr);
            }

            /* Move first image to second column */
            .about__image:nth-child(1) {
                grid-area: 1/2/2/3;
            }

            /* Create offset effect */
            .about__image:nth-child(1),
            .about__card:nth-child(4) {
                transform: translateY(2rem);
            }
        }

        /* Desktop (768px and up) */
        @media (width > 768px) {
            .about__container {
                grid-template-columns: repeat(2, 1fr);
                align-items: center;
            }

            .about::before {
                height: 75%;
                width: 75%;
            }

            .section__header {
                font-size: 2.5rem;
            }
        }

        /* Large Desktop (1024px and up) */
        @media (width > 1024px) {
            .about__grid {
                gap: 2rem;
            }

            .section__header {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <!-- About Section -->
    <section class="about" id="about">
        <div class="section__container about__container">
            <div class="about__grid">
                <div class="about__image">
                    <img src="assets/about-1.jpg" alt="Verified Hostels" />
                </div>
                <div class="about__card">
                    <span><i class="ri-hotel-line"></i></span>
                    <h4>Verified Hostels</h4>
                    <p>
                        We partner only with trusted and verified hostels to ensure your safety and peace of mind.
                    </p>
                </div>
                <div class="about__image">
                    <img src="assets/about-2.jpg" alt="Luxury Rooms" />
                </div>
                <div class="about__card">
                    <span><i class="ri-calendar-check-line"></i></span>
                    <h4>Luxury Room</h4>
                    <p>Experience unrivaled luxury at our exquisite luxury rooms.</p>
                </div>
            </div>
            <div class="about__content">
                <p class="section__subheader">ABOUT US</p>
                <h2 class="section__header">Uncover Hidden Hostels Near You</h2>
                <p class="section__description">
                    At Dormify, we connect you with verified hostels that fit your budget, location preferences, and lifestyle needs. From cozy rooms near your college to vibrant community hostels in the heart of the city, we make sure you find a space that feels like home.
                </p>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
