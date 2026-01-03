<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "sm";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$alert = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['t2'] ?? '');
    $user_password = trim($_POST['t6'] ?? '');

    if (!empty($email) && !empty($user_password)) {

        $stmt = $conn->prepare("SELECT * FROM register WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($user_password, $user['password'])) {
                // ✅ Login successful - Store ALL user data in session
                $_SESSION['reg_id'] = $user['reg_id']; // ADD THIS LINE - Important!
                $_SESSION['user_id'] = $user['reg_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['phoneno'];
                $_SESSION['user_address'] = $user['address'];
                $_SESSION['user_qualification'] = $user['quali'];
                $_SESSION['logged_in'] = true;

                // Check if there's a redirect parameter for booking flow
                $redirect_url = 'index.php';
                if (isset($_GET['redirect']) && $_GET['redirect'] == 'rooms1') {
                    $redirect_url = 'rooms1.php';
                }

                $alert = "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome Back!',
                        text: 'Login successful!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '$redirect_url';
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
                    }).then(() => {
                        window.location.href = 'index.php';
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
                }).then(() => {
                    window.location.href = 'index.php';
                }); 
            </script>";
        }

        $stmt->close();
    } else {
        // Empty fields
        $alert = "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in all fields!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>

<body>
    <?php echo $alert; ?>
</body>

</html>
