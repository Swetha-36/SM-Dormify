<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hostels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        
        <form action="hostel1.php" method="GET" class="row g-3 align-items-end">
            <!-- Room Type (33% width) -->
            <div class="col-md-3 col-sm-12">
                <label class="form-label fw-bold">Room Type</label>
                <select name="room_type" class="form-select" required>
                    <option value="">Any Room</option>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Triple">Triple</option>
                    <option value="Four Sharing">4-Sharing</option>
                </select>
            </div>

            <!-- Price Range (33% width) -->
            <div class="col-md-3 col-sm-12">
                <label class="form-label fw-bold">Max Price</label>
                <select name="price_range" class="form-select" required>
                    <option value="">Any Price</option>
                    <option value="5000">₹5,000</option>
                    <option value="8000">₹8,000</option>
                    <option value="12000">₹12,000</option>
                    <option value="15000">₹15,000</option>
                </select>
            </div>

            <!-- Location (33% width) -->
            <div class="col-md-3 col-sm-12">
                <label class="form-label fw-bold">Location</label>
                <input type="text" name="location" class="form-control" 
                       placeholder="Khammam, Mumbai..." required>
            </div>

            <!-- Search Button (Full width on mobile) -->
            <div class="col-md-3 col-sm-12">
                <button type="submit" class="btn btn-primary w-100 h-100">
                    <i class="bi bi-search me-2"></i>Search Hostels
                </button>
            </div>
        </form>
    </div>
</body>
</html>
