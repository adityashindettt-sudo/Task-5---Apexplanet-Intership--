<?php
require_once __DIR__ . '/../../src/db.php';
$stmt = $pdo->prepare("SELECT DATE(enrolled_at) as d, COUNT(*) as cnt FROM enrollments WHERE enrolled_at >= DATE_SUB(CURDATE(), INTERVAL 29 DAY) GROUP BY d ORDER BY d");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$labels = []; $values = [];
$period = new DatePeriod(new DateTime('-29 days'), new DateInterval('P1D'), 30);
$map = [];
foreach ($rows as $r) $map[$r['d']] = (int)$r['cnt'];
foreach ($period as $dt){
  $d = $dt->format('Y-m-d');
  $labels[] = $d;
  $values[] = $map[$d] ?? 0;
}
header('Content-Type: application/json');
echo json_encode(['labels'=>$labels,'values'=>$values]);
