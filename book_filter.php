
<?php
// Get search parameters from booking form
$room_type = isset($_POST['room_type']) && !empty($_POST['room_type']) ? $_POST['room_type'] : '';
$price_range = isset($_POST['price_range']) && !empty($_POST['price_range']) ? $_POST['price_range'] : '';
$location = isset($_POST['location']) && !empty($_POST['location']) ? $_POST['location'] : '';

// Build URL parameters
$params = [];
if (!empty($room_type)) {
    $params[] = 'room_type=' . urlencode($room_type);
}
if (!empty($price_range)) {
    $params[] = 'price_range=' . urlencode($price_range);
}
if (!empty($location)) {
    $params[] = 'location=' . urlencode($location);
}

// Redirect with parameters
$query_string = !empty($params) ? '?' . implode('&', $params) : '';
header("Location: rooms1.php" . $query_string);
exit();
?>
