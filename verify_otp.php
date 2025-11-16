<?php
require_once __DIR__ . '/../src/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';
    $stmt = $pdo->prepare("SELECT id, expires_at, used FROM otp_verifications WHERE email=? AND otp_code=? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$email, $otp]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) { $error = 'Invalid OTP'; }
    elseif ($row['used']) { $error = 'OTP already used'; }
    elseif (new DateTime() > new DateTime($row['expires_at'])) { $error = 'OTP expired'; }
    else {
        $upd = $pdo->prepare("UPDATE otp_verifications SET used=1 WHERE id=?");
        $upd->execute([$row['id']]);
        if (!isset($_SESSION['pending_user'])) { $error = 'Session expired. Please register again.'; }
        else {
            $u = $_SESSION['pending_user'];
            $ins = $pdo->prepare("INSERT INTO users (name,email,password,is_verified) VALUES (?,?,?,1)");
            $ins->execute([$u['name'], $u['email'], $u['password']]);
            unset($_SESSION['pending_user']);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
$email_get = htmlspecialchars($_GET['email'] ?? '');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Verify OTP</title></head><body>
<h2>Verify OTP</h2>
<?php if(!empty($error)) echo '<p style="color:red;">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <input type="hidden" name="email" value="<?php echo $email_get; ?>">
  <label>Enter OTP sent to <?php echo $email_get; ?></label><br>
  <input name="otp" required><br>
  <button type="submit">Verify</button>
</form>
</body></html>
