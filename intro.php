<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intro Video</title>
    
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

        /* MISSING: Video element styles */
        video {
            width: 100%;
            display: block;
            border-radius: 8px;
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
            line-height: 1.2;
        }

        .section__description {
            margin-top: 1rem;
            color: var(--text-light);
            line-height: 1.6;
            font-size: 1rem;
        }

        .intro {
            position: relative;
            isolation: isolate;
            background-color: var(--primary-color);
            overflow: hidden; /* ADDED: Prevents overflow issues */
        }

        .intro::before {
            position: absolute;
            content: "";
            right: 0;
            top: 0; /* ADDED: Position from top */
            height: 100%;
            width: calc(100vw / 4);
            background-color: var(--secondary-color);
            z-index: -1;
        }

        .intro__container {
            display: grid;
            gap: 4rem 2rem;
        }

        /* ADDED: Content container styles */
        .intro__content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .intro__container .section__subheader {
            color: var(--secondary-color);
        }

        .intro__container :is(.section__header, .section__description) {
            margin-bottom: 1rem;
            color: var(--white);
        }

        .intro__video {
            max-width: 450px;
            margin: auto;
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden; /* ADDED: Contains video border-radius */
        }

        /* Responsive styles */
        @media (width > 768px) {
            .intro__container {
                grid-template-columns: repeat(2, 1fr);
                align-items: center;
            }

            /* ADDED: Better video sizing on desktop */
            .intro__video {
                max-width: 550px;
            }
        }

        /* ADDED: Mobile optimization */
        @media (width < 768px) {
            .section__header {
                font-size: 2rem;
            }

            .intro::before {
                width: 50%; /* More visible on mobile */
                opacity: 0.3; /* Subtle on mobile */
            }
        }

        /* ADDED: Tablet styles */
        @media (width >= 768px) and (width <= 1024px) {
            .intro::before {
                width: calc(100vw / 3); /* Slightly wider on tablets */
            }
        }
    </style>
</head>
<body>
    <section class="intro">
        <div class="section__container intro__container">
            <div class="intro__content">
                <p class="section__subheader">INTRO VIDEO</p>
                <h2 class="section__header">Meet With Our Luxury Place</h2>
                <p class="section__description">
                    "Whether you're looking for a cozy private room or a vibrant shared space, our hostels offer the perfect mix of comfort, community, and convenience — making every stay an experience to remember."
                </p>
            </div>
            <div class="intro__video">
                <video src="assets/luxury.mp4" autoplay muted loop playsinline></video>
            </div>
        </div>
    </section>
</body>
</html>
