<?php
session_start();
require_once 'db.php';

$alert = '';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $alert = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Missing Fields',
                text: 'Please fill in all fields!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else {
        $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                
                $alert = "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome Back!',
                        text: 'Login successful!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'dashboard.php';
                    });
                </script>";
            } else {
                // Wrong password
                $alert = "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Wrong Password',
                        text: 'Please check your password!',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
        } else {
            // User doesn't exist
            $alert = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'User does not exist!',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Hostel Booking</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #fcf5f6ff 0%, #201729ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container { 
            width: 400px; 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h2 { 
            margin-bottom: 30px; 
            color: #333; 
            text-align: center;
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
        input[type="email"], input[type="password"] {
            width: 100%; 
            padding: 12px; 
            border: 2px solid #ddd; 
            border-radius: 6px; 
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }
        button { 
            width: 100%; 
            padding: 14px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <?php echo $alert; ?>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="admin@hostel.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
