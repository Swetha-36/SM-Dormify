<?php
session_start(); // Start session at the very beginning

$host     = "localhost:3306";
$user     = "root";
$password = "";
$dbname   = "sm";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed");
}

$alert = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname      = trim($_POST['t1']);
    $email         = trim($_POST['t2']);
    $phone         = trim($_POST['t3']);
    $address       = trim($_POST['t4']);
    $qualification = trim($_POST['t5']);
    $password      = $_POST['t6'];
    $confirm_pass  = $_POST['t7'];

    $errors = [];

    // required fields first
    if (empty($fullname) || empty($address) || empty($qualification) || empty($password) || empty($confirm_pass)) {
        $errors[] = "All fields are required.";
    }

    // password match
    if ($password !== $confirm_pass) {
        $errors[] = "Passwords do not match!";
    }

    // password strength (recommended)
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // phone digits
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Phone number must be 10 digits.";
    }

    // check duplicate email
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT reg_id FROM register WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $stmt->close();
    }

    // insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
            "INSERT INTO register (name, email, phoneno, address, quali, password) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssssss",
            $fullname,
            $email,
            $phone,
            $address,
            $qualification,
            $hashedPassword
        );

        if ($stmt->execute()) {
            // ✅ Get the inserted user's ID
            $user_id = $stmt->insert_id;
            
            // ✅ Store user data in session (auto-login after registration)
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $fullname;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_address'] = $address;
            $_SESSION['user_qualification'] = $qualification;
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            $conn->close();
            
            $alert = "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: 'Welcome! You are now logged in.',
                    confirmButtonText: 'Continue',
                    confirmButtonColor: '#28a745',
                    timer: 2000
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
            
        } else {
            $errors[] = "Registration failed: " . $stmt->error;
        }
        
    }

    // show popup errors
    if (!empty($errors)) {
        $errorList = "";
        foreach ($errors as $error) {
            $errorList .= "• " . htmlspecialchars($error) . "<br>";
        }
        
        $alert = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                html: '$errorList',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>

<?php echo $alert; ?>

</body>
</html>
