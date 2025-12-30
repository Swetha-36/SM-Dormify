<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="card" style="max-width: 500px; margin: 50px auto;">
        <div class="card-body text-center">
            <div style="font-size: 80px; color: #dc3545;">✗</div>
            <h2 class="text-danger mb-3">Payment Failed</h2>
            <p>Your payment could not be processed. Please try again.</p>
            <a href="rooms.php" class="btn btn-primary mt-3">Back to Rooms</a>
        </div>
    </div>
</div>
</body>
</html>

