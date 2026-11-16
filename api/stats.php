<?php
header('Content-Type: application/json');

$pdo = require __DIR__ . '/db.php';

// Count projects per status
$stmt = $pdo->prepare("SELECT status, COUNT(*) AS total FROM projects GROUP BY status");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$counts = ['planned' => 0, 'progress' => 0, 'waiting' => 0, 'done' => 0];
foreach ($rows as $r) {
    $counts[$r['status']] = (int) $r['total'];
}

// All projects for the table
$stmt = $pdo->prepare("SELECT id, title, customer_name, status FROM projects ORDER BY created_at DESC");
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Updates per week for the chart
$stmt = $pdo->prepare("SELECT YEARWEEK(created_at, 3) AS wk, COUNT(*) AS total FROM updates GROUP BY wk ORDER BY wk");
$stmt->execute();
$weeks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$updateCounts = [];
foreach ($weeks as $w) {
    $labels[] = 'Uge ' . substr($w['wk'], 4);
    $updateCounts[] = (int) $w['total'];
}

echo json_encode([
    'counts' => $counts,
    'projects' => $projects,
    'updatesPerWeek' => ['labels' => $labels, 'counts' => $updateCounts],
], JSON_UNESCAPED_UNICODE);