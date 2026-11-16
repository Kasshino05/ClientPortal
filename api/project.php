<?php
header('Content-Type: application/json');

$token = $_GET['t'] ?? '';

if (strlen($token) !== 32 || !ctype_xdigit($token)) {
    http_response_code(404);
    exit(json_encode(['error' => 'Not found']));
}

$pdo = require __DIR__ . '/db.php';

$stmt = $pdo->prepare('SELECT * FROM projects WHERE token = ?');
$stmt->execute([$token]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    http_response_code(404);
    exit(json_encode(['error' => 'Not found']));
}

$stmt = $pdo->prepare('SELECT title, done FROM milestones WHERE project_id = ? ORDER BY sort_order');
$stmt->execute([$p['id']]);
$p['milestones'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT message, created_at FROM updates WHERE project_id = ? ORDER BY created_at DESC');
$stmt->execute([$p['id']]);
$p['updates'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

unset($p['id'], $p['token']);

echo json_encode($p);