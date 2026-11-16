<?php
$c = require __DIR__ . '/config.php';

$pdo = new PDO(
    "mysql:host={$c['host']};port={$c['port']};dbname={$c['name']};charset=utf8mb4",
    $c['user'],
    $c['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

return $pdo;