<?php
require_once __DIR__ . '/../src/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$password) { 
        $error = 'Missing fields'; 
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) { 
            $error = 'Email already registered'; 
        } else {
            // Store user in session before OTP verification
            $_SESSION['pending_user'] = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            // Generate OTP
            $otp = rand(100000, 999999);
            $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            // Insert OTP into DB
            $ins = $pdo->prepare("INSERT INTO otp_verifications (email, otp_code, expires_at, used) 
                                  VALUES (?, ?, ?, 0)");
            $ins->execute([$email, $otp, $expires]);

            // === IMPORTANT PART ===
            // XAMPP CANNOT SEND EMAIL → So show OTP on screen
            echo "<h2>Registration Successful!</h2>";
            echo "<p>Your OTP is:</p>";
            echo "<h1 style='color:green;'>$otp</h1>";
            echo "<p>Enter this OTP on the verification page.</p>";
            echo "<a href='verify_otp.php?email=" . urlencode($email) . "'>Go to OTP Verification</a>";
            exit;
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
</head>
<body>

<h2>Register</h2>

<?php 
if (!empty($error)) {
    echo '<p style="color:red;">'.htmlspecialchars($error).'</p>';
}
?>

<form method="post">
  <label>Name</label><br>
  <input name="name" required><br>

  <label>Email</label><br>
  <input name="email" type="email" required><br>

  <label>Password</label><br>
  <input name="password" type="password" required><br><br>

  <button type="submit">Register</button>
</form>

</body>
</html>
