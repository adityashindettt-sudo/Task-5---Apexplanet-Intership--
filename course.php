<?php
require_once __DIR__ . '/../src/db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM courses WHERE id=?');
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Course</title></head><body>
<?php if(!$c) echo '<p>Course not found</p>'; else { ?>
  <h2><?php echo htmlspecialchars($c['title']); ?></h2>
  <p><?php echo nl2br(htmlspecialchars($c['description'])); ?></p>
<?php } ?>
<p><a href="/">Home</a></p>
</body></html>
