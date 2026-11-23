<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Admin – Client Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="app">

    <aside class="sidebar">
        <div class="logo">CP</div>
        <nav>
            <a href="index.php" class="active">Dashboard</a>
            <a href="new-project.php">Nyt projekt</a>
            <a href="logout.php">Log ud</a>
        </nav>
    </aside>

    <main class="main">
        <h1>Dashboard</h1>

        <div class="stats">
            <div class="card stat">
                <div class="label">Aktive projekter</div>
                <div class="number" id="stat-active">0</div>
            </div>
            <div class="card stat">
                <div class="label">Venter på kunde</div>
                <div class="number" id="stat-waiting">0</div>
            </div>
            <div class="card stat">
                <div class="label">Færdige</div>
                <div class="number" id="stat-done">0</div>
            </div>
        </div>

        <div class="charts">
            <div class="card">
                <h2>Projekter efter status</h2>
                <canvas id="status-chart"></canvas>
            </div>
            <div class="card">
                <h2>Opdateringer pr. uge</h2>
                <canvas id="updates-chart"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Projekter</h2>
            <table class="projects">
                <thead>
                    <tr>
                        <th>Projekt</th>
                        <th>Kunde</th>
                        <th>Status</th>
                        <th>Fremskridt</th>
                    </tr>
                </thead>
                <tbody id="project-rows"></tbody>
            </table>
        </div>
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="admin.js"></script>
</body>
</html>