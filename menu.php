<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    
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
        }

        /* MISSING: Image styles */
        img {
            width: 100%;
            display: block;
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

        /* MISSING: Menu section background */
        .menu {
            background-color: var(--extra-light);
        }

        /* MISSING: Menu header styles */
        .menu__header {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* MISSING: Navigation arrows styles */
        .section__nav {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section__nav span {
            padding: 10px 15px;
            font-size: 1.25rem;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            background-color: var(--white);
            color: var(--text-dark);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .section__nav span:hover {
            background-color: var(--secondary-color);
            color: var(--white);
        }

        /* MISSING: Menu items list styles */
        .menu__items {
            list-style: none;
            margin-block: 4rem;
            display: grid;
            gap: 1rem 4rem;
        }

        .menu__items li {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: 0.3s;
            padding: 1rem;
            border-radius: 8px;
            background-color: var(--white);
        }

        .menu__items li:hover {
            background-color: var(--primary-color);
            transform: translateX(10px);
        }

        /* MISSING: Menu image styles */
        .menu__items img {
            max-width: 100px;
            min-width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* MISSING: Menu details container */
        .menu__details {
            flex: 1;
        }

        .menu__details h4 {
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--text-dark);
            transition: 0.3s;
        }

        .menu__details p {
            max-width: 400px;
            color: var(--text-light);
            transition: 0.3s;
            line-height: 1.6;
        }

        /* MISSING: Hover effect for text */
        .menu__items li:hover :is(h4, p) {
            color: var(--white);
        }

        /* Responsive styles */
        @media (width > 768px) {
            .menu__header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .menu__items {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Mobile optimization */
        @media (width < 768px) {
            .section__header {
                font-size: 2rem;
            }

            .menu__items li {
                flex-direction: column;
                text-align: center;
            }

            .menu__items img {
                max-width: 100%;
                height: 200px;
            }

            .section__nav {
                justify-content: center;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <section class="menu" id="menu">
        <div class="section__container menu__container">
            <div class="menu__header">
                <div>
                    <p class="section__subheader">MENU</p>
                    <h2 class="section__header">Our Food Menu</h2>
                </div>
                <div class="section__nav">
                    <span><i class="ri-arrow-left-line"></i></span>
                    <span><i class="ri-arrow-right-line"></i></span>
                </div>
            </div>
            <ul class="menu__items">
                <li>
                    <img src="assets/menu-1.jpg" alt="Breakfast" />
                    <div class="menu__details">
                        <h4>Breakfast</h4>
                        <p>Healthy and energizing breakfast options served fresh to kick-start your day.</p>
                    </div>
                </li>
                <li>
                    <img src="assets/menu-2.jpg" alt="Veg" />
                    <div class="menu__details">
                        <h4>Veg</h4>
                        <p>Nutritious and well-balanced vegetarian meals prepared with fresh ingredients for daily nourishment.</p>
                    </div>
                </li>
                <li>
                    <img src="assets/menu-3.jpg" alt="Non-veg" />
                    <div class="menu__details">
                        <h4>Non-veg</h4>
                        <p>Protein-rich non-vegetarian dishes prepared hygienically and served on selected days.</p>
                    </div>
                </li>
                <li>
                    <img src="assets/menu-4.jpg" alt="snacks" />
                    <div class="menu__details">
                        <h4>snacks</h4>
                        <p>Light and tasty evening snacks served with beverages to keep you refreshed.</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</body>
</html>
