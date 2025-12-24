<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    
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

        /* MISSING: Link styles */
        a {
            text-decoration: none;
        }

        .section__container {
            max-width: var(--max-width);
            margin: auto;
            padding: 5rem 1rem;
        }

        .section__description {
            margin-top: 1rem;
            color: var(--text-light);
            line-height: 1.6;
        }

        /* MISSING: Logo styles */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: var(--text-light);
            letter-spacing: 2px;
        }

        .logo div {
            padding-inline: 12px;
            font-size: 2rem;
            border-radius: 5px;
        }

        .footer {
            background-color: var(--primary-color);
        }

        .footer__container {
            display: grid;
            gap: 4rem 2rem;
        }

        .footer__logo {
            margin-bottom: 2rem;
            color: var(--white);
        }

        .footer__logo div {
            background-color: var(--secondary-color);
            color: var(--white); /* ADDED */
        }

        /* ADDED: Logo span styling */
        .footer__logo span {
            color: var(--white);
            font-size: 1.2rem;
        }

        .footer__socials {
            list-style: none;
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .footer__socials a {
            padding: 6px 10px;
            font-size: 1.25rem;
            color: var(--text-light);
            background-color: var(--white);
            border-radius: 100%;
            cursor: pointer;
            transition: 0.3s;
            display: flex; /* ADDED: Centers icon */
            align-items: center;
            justify-content: center;
            width: 40px; /* ADDED: Fixed size */
            height: 40px; /* ADDED: Fixed size */
        }

        .footer__socials a:hover {
            color: var(--white);
            background-color: var(--secondary-color);
            transform: translateY(-3px); /* ADDED: Hover lift effect */
        }

        .footer__col h4 {
            margin-bottom: 2rem;
            font-size: 1.25rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--white);
        }

        .footer__links {
            list-style: none;
        }

        .footer__links li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .footer__links a {
            color: var(--text-light);
            transition: 0.3s;
        }

        .footer__links a:hover {
            color: var(--secondary-color);
        }

        .footer__col:last-child .footer__links li {
            margin-bottom: 2rem;
            align-items: flex-start; /* ADDED: Aligns icon to top */
        }

        .footer__links span {
            font-size: 2rem;
            color: var(--secondary-color);
            display: flex; /* ADDED: Centers icon */
            align-items: center;
            justify-content: center;
            min-width: 40px; /* ADDED: Consistent width */
        }

        /* ADDED: Contact info container */
        .footer__links div {
            flex: 1;
        }

        .footer__links h5 {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            font-weight: 800;
            font-family: var(--header-font);
            color: var(--white);
        }

        .footer__links p {
            color: var(--text-light);
            line-height: 1.4;
        }

        .footer__bar {
            padding: 1rem;
            font-size: 0.8rem;
            color: var(--text-light);
            text-align: center;
            background-color: rgba(0, 0, 0, 0.2); /* ADDED: Subtle background */
        }

        /* Responsive styles */
        @media (width > 480px) {
            .footer__container {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer__col:first-child {
                grid-column: 1/3;
            }
        }

        @media (width > 768px) {
            .footer__container {
                grid-template-columns: 2fr repeat(2, 1fr);
            }

            .footer__col:first-child {
                grid-column: unset;
            }
        }
    </style>
</head>
<body>
    <footer class="footer">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="logo footer__logo">
                    <div>SM</div>
                    <span>DORMIFY</span>
                </div>
                <p class="section__description">
                    Discover safe, affordable, and verified hostels near you with SM Dormify.
                </p>
                <ul class="footer__socials">
                    <li><a href="#" aria-label="YouTube"><i class="ri-youtube-fill"></i></a></li>
                    <li><a href="#" aria-label="Instagram"><i class="ri-instagram-line"></i></a></li>
                    <li><a href="#" aria-label="Facebook"><i class="ri-facebook-fill"></i></a></li>
                    <li><a href="#" aria-label="LinkedIn"><i class="ri-linkedin-fill"></i></a></li>
                </ul>
            </div>

            <div class="footer__col">
                <h4>Services</h4>
                <ul class="footer__links">
                    <li><a href="#">Online Booking</a></li>
                    <li><a href="#">Room Customization</a></li>
                    <li><a href="#">Virtual Tours</a></li>
                    <li><a href="#">Special Offers</a></li>
                    <li><a href="#">Concierge Services</a></li>
                    <li><a href="#">Customer Support</a></li>
                </ul>
            </div>

            <div class="footer__col">
                <h4>Contact Us</h4>
                <ul class="footer__links">
                    <li>
                        <span><i class="ri-phone-fill"></i></span>
                        <div>
                            <h5>Phone Number</h5>
                            <p>+91 9876543210</p>
                        </div>
                    </li>
                    <li>
                        <span><i class="ri-record-mail-line"></i></span>
                        <div>
                            <h5>Email</h5>
                            <p>info@smdormify.com</p>
                        </div>
                    </li>
                    <li>
                        <span><i class="ri-map-pin-2-fill"></i></span>
                        <div>
                            <h5>Location</h5>
                            <p>First Street, NYC</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer__bar">
            Copyright © 2025 SM Dormify. All rights reserved.
        </div>
    </footer>
</body>
</html>

