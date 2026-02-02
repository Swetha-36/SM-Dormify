<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMDormify</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
        }

        /* Background Image */
        .home {
            height: 100vh;
            background: linear-gradient(
                rgba(0,0,0,0.35),
                rgba(0,0,0,0.35)
            ),
            url("https://i.pinimg.com/736x/b3/15/52/b315527f272a1a00df44206a286308b7.jpg"); /* your image name */
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Center Text */
        .content h1 {
            color: #fff;
            font-size: 48px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .content p {
            color: #eaeaea;
            font-size: 18px;
            margin-top: 10px;
        }

        /* Bottom Corner Button */
        .enter-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            padding: 10px 18px;
            font-size: 14px;
            border: none;
            border-radius: 30px;
            background: #ffffff;
            color: #000;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }

        .enter-btn:hover {
            background: #000;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="home">
    <div class="content">
        <h1>Welcome to SMDormify</h1>
        <p>Your smart student living platform</p>
    </div>

    <button class="enter-btn" onclick="openSite()">
        Enter Site →
    </button>
</div>

<script>
    function openSite() {
        window.location.href = "index.php"; 
        // change if needed
    }
</script>

</body>
</html>
