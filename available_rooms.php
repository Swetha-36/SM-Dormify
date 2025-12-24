<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rooms with Filters</title>
<script>
  const priceRange = document.getElementById('priceRange');
  const priceValue = document.getElementById('priceValue');
  if (priceRange && priceValue) {
    priceRange.addEventListener('input', () => {
      priceValue.textContent = '₹' + priceRange.value;
    });
  }
</script>

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
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Poppins", sans-serif;
      background-color: #f9f9f9;
    }

    img {
      width: 100%;
      display: block;
    }

    /* LAYOUT: FILTERS LEFT, ROOMS RIGHT */
    .rooms-layout {
      max-width: var(--max-width);
      margin: 2rem auto;
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 2rem;
      padding-inline: 1rem;
    }

    /* FILTER SIDEBAR */
    .filters {
      background: var(--white);
      border-radius: 8px;
      padding: 1.5rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      height: fit-content;
    }

    .filters h3 {
      font-family: var(--header-font);
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--text-dark);
    }

    .filter-group {
      margin-bottom: 1.5rem;
    }

    .filter-group h4 {
      font-size: 0.95rem;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .filter-group label {
      display: block;
      font-size: 0.85rem;
      color: var(--text-light);
      margin-bottom: 0.25rem;
      cursor: pointer;
    }

    .filter-group input[type="checkbox"] {
      margin-right: 0.4rem;
    }

    .filter-group input[type="range"] {
      width: 100%;
    }

    .filter-actions {
      display: flex;
      gap: 0.5rem;
    }

    .btn {
      border: none;
      border-radius: 4px;
      padding: 0.5rem 0.9rem;
      font-size: 0.85rem;
      cursor: pointer;
    }

    .btn-primary {
      background: var(--secondary-color);
      color: var(--white);
    }

    .btn-outline {
      background: transparent;
      color: var(--text-dark);
      border: 1px solid #e2e8f0;
    }

    /* ROOMS SECTION (RIGHT) */
    .room__container {
      padding-block: 0;
      padding-inline: 0;
    }

    .room__container :is(.section__subheader, .section__header) {
      padding-inline: 0;
      text-align: left;
    }

    .section__subheader {
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--text-light);
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .section__header {
      font-size: 2.2rem;
      font-weight: 800;
      font-family: var(--header-font);
      color: var(--text-dark);
      margin-bottom: 1rem;
    }

    .room__grid {
  max-width: 100%;
  margin-inline: 0;
  margin-top: 2rem;
  display: grid;
  gap: 2rem;
  grid-template-columns: 1fr; /* single column */
}

    .room__card {
      position: relative;
      overflow: visible;
    }

    .room__card img {
      box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      height: 260px;
      object-fit: cover;
    }

    .room__card__details {
      margin-inline: 1rem;
      padding: 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      background-color: var(--white);
      transform: translateY(-50%);
      border-radius: 5px;
      box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
    }

    .room__card__details h4 {
      margin-bottom: 0.5rem;
      font-size: 1.2rem;
      font-weight: 800;
      font-family: var(--header-font);
      color: var(--text-dark);
    }

    .room__card__details p {
      color: var(--text-light);
      font-size: 0.9rem;
      line-height: 1.4;
    }

    .room__card__details h3 {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--secondary-color);
      white-space: nowrap;
    }

    .room__card__details h3 span {
      font-size: 0.8rem;
      color: var(--text-light);
    }

    
    
    /* RESPONSIVE: STACK FILTERS ABOVE ROOMS ON SMALL SCREENS */
    @media (max-width: 768px) {
      .rooms-layout {
        grid-template-columns: 1fr;
      }
    }

    @media (width < 480px) {
      .section__header {
        font-size: 2rem;
      }

      .room__card__details {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
      }

      .room__card__details h3 {
        align-self: flex-end;
      }
    }
  </style>
</head>
<body>
  

  <!-- NEW LAYOUT: FILTERS + ROOMS -->
   <div class="rooms-layout">
 <aside class="filters">
  <h3>Filter Rooms</h3>

  <!-- 1. Room type -->
  <div class="filter-group">
    <h4>Room type</h4>
    <label><input type="checkbox" name="room_type" value="single" /> Single</label>
    <label><input type="checkbox" name="room_type" value="double" /> Double</label>
    <label><input type="checkbox" name="room_type" value="triple" /> Triple</label>
    <label><input type="checkbox" name="room_type" value="common_sharing" /> Common sharing</label>
  </div>

  <!-- 2. Price -->
  <div class="filter-group">
    <h4>Price (per month)</h4>

    <!-- Min – Max slider (simple single range; replace with dual range if needed) -->
    

    <!-- Predefined ranges -->
    <div style="margin-top: 0.75rem;">
      <h4 style="font-size: 0.9rem; margin-bottom: 0.25rem;"></h4>
      <label><input type="checkbox" name="price_range" value="3000-5000" /> ₹3,000–₹5,000</label>
      <label><input type="checkbox" name="price_range" value="5000-8000" /> ₹5,000–₹8,000</label>
      <label><input type="checkbox" name="price_range" value="8000-12000" /> ₹8,000–₹12,000</label>
      <label><input type="checkbox" name="price_range" value="12000-15000" /> ₹12,000–₹15,000</label>
    </div>
  </div>

  <!-- 3. Gender -->
  <div class="filter-group">
    <h4>Gender</h4>
    <label><input type="radio" name="gender" value="male" /> Male</label>
    <label><input type="radio" name="gender" value="female" /> Female</label>
  </div>

  <!-- 4. Rating -->
  <div class="filter-group">
    <h4>Rating</h4>
    <label><input type="radio" name="rating" value="1" /> 1+ stars</label>
    <label><input type="radio" name="rating" value="2" /> 2+ stars</label>
    <label><input type="radio" name="rating" value="3" /> 3+ stars</label>
    <label><input type="radio" name="rating" value="4" /> 4+ stars</label>
    <label><input type="radio" name="rating" value="5" /> 5 stars only</label>
  </div>

  <!-- 5. Occupancy type -->
  <div class="filter-group">
    <h4>Occupancy type</h4>
    <label><input type="radio" name="occupancy_type" value="student" /> Student</label>
    <label><input type="radio" name="occupancy_type" value="working" /> Working</label>
    <label><input type="radio" name="occupancy_type" value="both" /> Both</label>
  </div>

  <!-- 6. Room furnishing -->
  <div class="filter-group">
    <h4>Room furnishing</h4>
    <label><input type="checkbox" name="furnishing" value="fully" /> Fully furnished</label>
    <label><input type="checkbox" name="furnishing" value="semi" /> Semi-furnished</label>
    <label><input type="checkbox" name="furnishing" value="unfurnished" /> Unfurnished</label>
  </div>

  <!-- 7. Food facility -->
  <div class="filter-group">
    <h4>Food facility</h4>
    <label><input type="radio" name="food" value="veg" /> Veg</label>
    <label><input type="radio" name="food" value="nonveg" /> Non-veg</label>
    <label><input type="radio" name="food" value="both" /> Both</label>
  </div>

  <!-- 8. Amenities -->
  <div class="filter-group">
    <h4>Amenities</h4>
    <label><input type="checkbox" name="amenities" value="wifi" /> Wi‑Fi</label>
    <label><input type="checkbox" name="amenities" value="ac" /> AC / Non-AC</label>
    <label><input type="checkbox" name="amenities" value="geyser" /> Geyser</label>
    <label><input type="checkbox" name="amenities" value="washing-machine" /> Washing Machine</label>
    <label><input type="checkbox" name="amenities" value="study-table" /> Study Table</label>
    <label><input type="checkbox" name="amenities" value="cupboard-locker" /> Cupboard / Locker</label>
  </div>

  <!-- Actions -->
  <div class="filter-actions">
    <button class="btn btn-primary" type="submit">Apply</button>
    <button class="btn btn-outline" type="reset">Reset</button>
  </div>
</aside>


    <!-- RIGHT: ROOMS -->
    <section class="room__container" id="room">
      <p class="section__subheader">ROOMS</p>
      <h2 class="section__header">Hand Picked Rooms</h2>
      <div class="room__grid">
        <div class="room__card">
          <img src="assets/room-1.jpg" alt="Deluxe Suite room" />
          <div class="room__card__details">
            <div>
              <h4>Deluxe Suite</h4>
              <p>Well-appointed rooms designed for guests who desire a more.</p>
            </div>
            <h3>$399<span>/night</span></h3>
          </div>
        </div>
        <div class="room__card">
          <img src="assets/room-2.jpg" alt="Family Suite room" />
          <div class="room__card__details">
            <div>
              <h4>Family Suite</h4>
              <p>Consists of multiple rooms and a common living area.</p>
            </div>
            <h3>$599<span>/night</span></h3>
          </div>
        </div>
        <div class="room__card">
          <img src="assets/room-3.jpg" alt="Luxury Penthouse room" />
          <div class="room__card__details">
            <div>
              <h4>Luxury Penthouse</h4>
              <p>Top-tier accommodations usually on the highest floors of a hotel.</p>
            </div>
            <h3>$799<span>/night</span></h3>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
</body>
</html>
