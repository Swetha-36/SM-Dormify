<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Hostel Room</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .room-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .room-info h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .room-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #666;
        }
        
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #764ba2;
        }
        
        .btn-book {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-book:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: none;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏨 Book Your Hostel Room</h1>
        
        <form id="bookingForm">
            <div class="form-group">
                <label for="userName">Full Name *</label>
                <input type="text" id="userName" name="userName" required>
                <span class="error" id="nameError">Please enter your name</span>
            </div>
            
            <div class="form-group">
                <label for="userEmail">Email Address *</label>
                <input type="email" id="userEmail" name="userEmail" required>
                <span class="error" id="emailError">Please enter a valid email</span>
            </div>
            
            <div class="form-group">
                <label for="userPhone">Phone Number *</label>
                <input type="tel" id="userPhone" name="userPhone" pattern="[0-9]{10}" required>
                <span class="error" id="phoneError">Please enter a valid 10-digit phone number</span>
            </div>
            
            <div class="form-group">
                <label for="hostelName">Select Hostel *</label>
                <select id="hostelName" name="hostelName" required>
                    <option value="">Choose a hostel</option>
                    <option value="Sunrise Hostel">Sunrise Hostel</option>
                    <option value="Ocean View Hostel">Ocean View Hostel</option>
                    <option value="Mountain Stay Hostel">Mountain Stay Hostel</option>
                    <option value="City Center Hostel">City Center Hostel</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="roomType">Room Type *</label>
                <select id="roomType" name="roomType" required>
                    <option value="">Choose room type</option>
                    <option value="Single Bed" data-price="500">Single Bed - ₹500/night</option>
                    <option value="Double Bed" data-price="800">Double Bed - ₹800/night</option>
                    <option value="4-Bed Dorm" data-price="300">4-Bed Dorm - ₹300/night</option>
                    <option value="6-Bed Dorm" data-price="200">6-Bed Dorm - ₹200/night</option>
                </select>
            </div>
            
            <div class="room-info" id="roomInfo" style="display:none;">
                <h3>Booking Summary</h3>
                <div class="room-details">
                    <span>Hostel:</span>
                    <span id="selectedHostel">-</span>
                </div>
                <div class="room-details">
                    <span>Room Type:</span>
                    <span id="selectedRoom">-</span>
                </div>
                <div class="room-details">
                    <span>Duration:</span>
                    <span>1 Night</span>
                </div>
                <hr style="margin: 15px 0;">
                <div class="room-details">
                    <span>Total Amount:</span>
                    <span class="total-amount" id="totalAmount">₹0</span>
                </div>
            </div>
            
            <button type="submit" class="btn-book" id="bookBtn">
                Book Now & Pay
            </button>
            <div class="loader" id="loader"></div>
        </form>
    </div>

    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
    <script>
        // Form elements
        const bookingForm = document.getElementById('bookingForm');
        const roomTypeSelect = document.getElementById('roomType');
        const hostelSelect = document.getElementById('hostelName');
        const roomInfo = document.getElementById('roomInfo');
        const bookBtn = document.getElementById('bookBtn');
        const loader = document.getElementById('loader');
        
        // Update booking summary when room type changes
        roomTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            
            if (price) {
                document.getElementById('selectedRoom').textContent = selectedOption.value;
                document.getElementById('totalAmount').textContent = '₹' + price;
                roomInfo.style.display = 'block';
            } else {
                roomInfo.style.display = 'none';
            }
        });
        
        // Update hostel name in summary
        hostelSelect.addEventListener('change', function() {
            document.getElementById('selectedHostel').textContent = this.value;
        });
        
        // Handle form submission
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                return false;
            }
            
            // Get form data
            const formData = {
                userName: document.getElementById('userName').value,
                userEmail: document.getElementById('userEmail').value,
                userPhone: document.getElementById('userPhone').value,
                hostelName: document.getElementById('hostelName').value,
                roomType: document.getElementById('roomType').value,
                totalAmount: roomTypeSelect.options[roomTypeSelect.selectedIndex].getAttribute('data-price')
            };
            
            // Show loader
            bookBtn.style.display = 'none';
            loader.style.display = 'block';
            
            // Create Razorpay order by calling backend
            createRazorpayOrder(formData);
        });
        
        // Validate form fields
        function validateForm() {
            let isValid = true;
            
            const name = document.getElementById('userName').value.trim();
            const email = document.getElementById('userEmail').value.trim();
            const phone = document.getElementById('userPhone').value.trim();
            
            if (name.length < 3) {
                showError('nameError');
                isValid = false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('emailError');
                isValid = false;
            }
            
            const phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(phone)) {
                showError('phoneError');
                isValid = false;
            }
            
            return isValid;
        }
        
        // Show error message
        function showError(errorId) {
            const errorElement = document.getElementById(errorId);
            errorElement.style.display = 'block';
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 3000);
        }
        
        // Create Razorpay order via backend API
        function createRazorpayOrder(formData) {
            fetch('create_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Open Razorpay checkout
                    openRazorpayCheckout(data.order, formData);
                } else {
                    alert('Error creating order: ' + data.message);
                    resetButton();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                resetButton();
            });
        }
        
        // Open Razorpay checkout popup
        function openRazorpayCheckout(order, formData) {
            const options = {
                key: order.key_id, // Razorpay Key ID from backend
                amount: order.amount, // Amount in paise
                currency: order.currency,
                name: 'Hostel Booking System',
                description: formData.hostelName + ' - ' + formData.roomType,
                order_id: order.id, // Order ID from backend
                
                // Prefill customer details
                prefill: {
                    name: formData.userName,
                    email: formData.userEmail,
                    contact: formData.userPhone
                },
                
                // Theme customization
                theme: {
                    color: '#667eea'
                },
                
                // Payment success handler
                handler: function(response) {
                    // Payment successful - verify on backend
                    verifyPayment(response, formData);
                },
                
                // Payment modal closed without success
                modal: {
                    ondismiss: function() {
                        alert('Payment cancelled. Please try again.');
                        resetButton();
                    }
                }
            };
            
            const rzp = new Razorpay(options);
            
            // Handle payment failure
            rzp.on('payment.failed', function(response) {
                handlePaymentFailure(response);
            });
            
            // Open Razorpay checkout
            rzp.open();
            resetButton();
        }
        
        // Verify payment on backend
        function verifyPayment(paymentResponse, formData) {
            const verifyData = {
                razorpay_order_id: paymentResponse.razorpay_order_id,
                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                razorpay_signature: paymentResponse.razorpay_signature,
                bookingData: formData
            };
            
            fetch('verify_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(verifyData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to success page
                    window.location.href = 'success.php?booking_id=' + data.booking_id;
                } else {
                    alert('Payment verification failed: ' + data.message);
                    window.location.href = 'failure.php';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Verification error. Please contact support.');
            });
        }
        
        // Handle payment failure
        function handlePaymentFailure(response) {
            console.error('Payment failed:', response.error);
            alert('Payment failed: ' + response.error.description);
            window.location.href = 'failure.php?reason=' + encodeURIComponent(response.error.description);
        }
        
        // Reset button state
        function resetButton() {
            bookBtn.style.display = 'block';
            loader.style.display = 'none';
        }
    </script>
</body>
</html>
