<?php
require_once __DIR__ . '/../../src/db.php';
$q = $_GET['q'] ?? '';
$stmt = $pdo->prepare("SELECT id, title, description, thumbnail FROM courses WHERE title LIKE ? OR description LIKE ? LIMIT 20");
$like = "%$q%";
$stmt->execute([$like,$like]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($courses);
