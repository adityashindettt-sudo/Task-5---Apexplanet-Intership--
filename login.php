<?php
require_once __DIR__ . '/../src/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT id, password, role, is_verified FROM users WHERE email=?");
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($password, $u['password'])) {
        if (!$u['is_verified']) { $error = 'Verify email first'; }
        else {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['role'] = $u['role'];
            header('Location: dashboard.php');
            exit;
        }
    } else { $error = 'Invalid credentials'; }
}
$reg = isset($_GET['registered']) ? true : false;
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>
<h2>Login</h2>
<?php if($reg) echo '<p style="color:green;">Registration successful. Please login.</p>'; ?>
<?php if(!empty($error)) echo '<p style="color:red;">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <label>Email</label><br><input name="email" type="email" required><br>
  <label>Password</label><br><input name="password" type="password" required><br>
  <button type="submit">Login</button>
</form>
</body></html>
