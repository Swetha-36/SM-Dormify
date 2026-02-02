<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Remixicon (for icons) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Your Custom CSS -->
    <!-- Global styles for whole site -->
    <link rel="stylesheet" href="style.css?v=3">

    <title>SM DORMIFY</title>

    <!-- ✅ INLINE STYLES TO OVERRIDE BOOTSTRAP BUTTONS -->
    <style>
        /* Force yellow buttons for Login/Register modals */
        #loginModal .btn-primary,
        #registerModal .btn-primary {
            background-color: #f6ac0f !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 14px 0 !important;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }

        #loginModal .btn-primary:hover,
        #registerModal .btn-primary:hover {
            background-color: #d89a0d !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(246, 172, 15, 0.4) !important;
        }
    </style>
</head>

<body>
    <!-- 🔹 Navbar -->
    <nav>
        <div class="nav__bar">
            <div class="nav__header">
                <div class="logo nav__logo">
                    <div>SM</div>
                    <span>DORMIFY</span>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>

            <ul class="nav__links" id="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#rooms">Room</a></li>
                <li><a href="#feature">Feature</a></li>
                <li><a href= "#menu">Menu</a></li>
                <li>

                    <?php
                    session_start();
                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                        // Show profile icon button
                    ?>
                        <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2"
                            data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="ri-user-3-fill"></i>
                        </button>
                    <?php
                    } else {
                        // Show login button
                    ?>
                        <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2"
                            data-bs-toggle="modal" data-bs-target="#loginModal">
                            Login
                        </button>
                    <?php
                    }
                    ?>
                </li>
            </ul>

            </li>
            </ul>

            </li>
            </ul>
        </div>
    </nav>

    <!-- 🔹 Header -->
    <header class="header" id="home">
        <div class="section__container header__container">
            <h1>The Right Room,<br />Right Now</h1>
        </div>
    </header>

    <!-- 🔹 Booking Section -->
    <?php require('book.php'); ?>

    <!-- 🔹 About Section -->
    <?php require('about.php'); ?>

    <!-- 🔹 Rooms -->
    <?php require('rooms.php'); ?>

    <!-- 🔹 Intro Section -->
    <?php require('intro.php'); ?>

    <!-- 🔹 Features -->
    <?php require('features.php'); ?>

    <!-- 🔹 Menu Section -->
    <?php require('menu.php'); ?>

    <!-- 🔹 Footer -->
    <?php require('footer.php'); ?>

    <!-- 🔹 Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center" id="loginModalLabel">
                        <i class="ri-user-line fs-3 me-2 text-primary"></i>
                        Sign In / Login
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="login.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" name="t2" class="form-control shadow-none"
                                placeholder="Enter your email" required />
                            <div id="emailHelp" class="form-text">
                                We'll never share your email with anyone else.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="t6" id="loginPassword"
                                    class="form-control shadow-none" placeholder="Enter your password" required />
                                <button class="btn btn-outline-secondary shadow-none" type="button"
                                    onclick="togglePassword('loginPassword', 'toggleLoginIcon')">
                                    
                                </button>
                            </div>
                        </div>

                        

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100 shadow-none mb-2">
                                Login
                            </button>
                        </div>

                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Don't have an account?
                                <a href="#" class="text-primary text-decoration-none"
                                    data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">
                                    Register here
                                </a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 🔹 Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center" id="registerModalLabel">
                        <i class="ri-user-add-line fs-3 me-2 text-primary"></i>
                        Create Account
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <form action="register.php" method="POST" id="registerForm">
                    <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="t1" class="form-control shadow-none"
                                placeholder="Enter your full name" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="t2" class="form-control shadow-none"
                                placeholder="example@email.com" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="t3" class="form-control shadow-none"
                                placeholder="Enter your phone number" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="t4" class="form-control shadow-none" rows="2"
                                placeholder="Enter your complete address" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Qualification</label>
                            <select name="t5" class="form-select shadow-none" required>
                                <option value="" selected disabled>Select your qualification</option>
                                <option value="High School">High School</option>
                                <option value="Diploma">Diploma</option>
                                <option value="Bachelor's Degree">Bachelor's Degree</option>
                                <option value="Master's Degree">Master's Degree</option>
                                <option value="PhD">PhD</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="t6" id="registerPassword"
                                    class="form-control shadow-none" placeholder="Create a strong password"
                                    minlength="8" required />
                                <button class="btn btn-outline-secondary shadow-none" type="button"
                                    onclick="togglePassword('registerPassword', 'toggleRegisterIcon')">
                                    <i class="ri-eye-line" id="toggleRegisterIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="t7" id="confirmPassword"
                                    class="form-control shadow-none" placeholder="Re-enter your password"
                                    minlength="8" required />
                                <button class="btn btn-outline-secondary shadow-none" type="button"
                                    onclick="togglePassword('confirmPassword', 'toggleConfirmIcon')">
                                    <i class="ri-eye-line" id="toggleConfirmIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Already have an account?
                                <a href="#" class="text-primary text-decoration-none"
                                    data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
                                    Login here
                                </a>
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer border-top pt-3">
                        <button type="submit" class="btn btn-primary w-100 shadow-none">
                            <i class="ri-user-add-line me-2"></i>Register Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Profile Details Modal -->
    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-primary bg-opacity-10">
                    <h5 class="modal-title d-flex align-items-center" id="profileModalLabel">
                        <i class="ri-user-3-line fs-4 me-2 text-primary"></i>
                        My Profile
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>

                        <!-- Profile Picture/Icon -->
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 100px; height: 100px;">
                                <i class="ri-user-3-fill" style="font-size: 3rem; color: #0f1a2c;"></i>
                            </div>
                            <h4 class="mt-3 mb-1 fw-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                            <p class="text-muted mb-0">Member</p>
                        </div>

                        <!-- Profile Details -->
                        <div class="card border-0 bg-light mb-3">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="ri-mail-line fs-5 text-primary me-3 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Email Address</small>
                                                <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="ri-phone-line fs-5 text-primary me-3 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Phone Number</small>
                                                <strong><?php echo htmlspecialchars($_SESSION['user_phone']); ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="d-flex align-items-start">
                                            <i class="ri-map-pin-line fs-5 text-primary me-3 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Address</small>
                                                <strong><?php echo htmlspecialchars($_SESSION['user_address']); ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex align-items-start">
                                            <i class="ri-graduation-cap-line fs-5 text-primary me-3 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Qualification</small>
                                                <strong><?php echo htmlspecialchars($_SESSION['user_qualification']); ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="window.location.href='#'">
                                <i class="ri-bookmark-line me-2"></i>My Bookings
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='#'">
                                <i class="ri-settings-3-line me-2"></i>Account Settings
                            </button>
                        </div>

                    <?php endif; ?>
                </div>

                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-2"></i>Close
                    </button>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="ri-logout-box-line me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('ri-eye-line');
                toggleIcon.classList.add('ri-eye-off-line');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('ri-eye-off-line');
                toggleIcon.classList.add('ri-eye-line');
            }
        }

        // Mobile menu toggle
        const menuBtn = document.getElementById('menu-btn');
        const navLinks = document.getElementById('nav-links');

        menuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });
    </script>

    <!-- ScrollReveal -->
    <?php

$is_logged_in = isset($_SESSION['reg_id']) && $_SESSION['reg_id'] > 0;
?>

<script>
const urlParams = new URLSearchParams(window.location.search);
const isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;

// Only show login modal if NOT logged in
if (urlParams.get('openLogin') === 'true' && !isLoggedIn) {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
    
    const redirect = urlParams.get('redirect');
    if (redirect) {
        const loginForm = document.querySelector('#loginModal form');
        if (loginForm) {
            const currentAction = loginForm.action || 'login.php';
            loginForm.action = currentAction + '?redirect=' + redirect;
        }
    }
}

// Clean up URL
if (urlParams.has('openLogin') || urlParams.has('redirect')) {
    const newUrl = window.location.pathname;
    window.history.replaceState({}, document.title, newUrl);
}
</script>



</body>

</html>