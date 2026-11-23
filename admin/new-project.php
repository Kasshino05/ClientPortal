<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<?php
// Create a new project. Saving needs the database (later).

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = require __DIR__ . '/../api/db.php';
    $customer_name = $_POST['customer_name'];
    $title = $_POST['title'];
    $preview_url = $_POST['preview_url'];
    $token = bin2hex(random_bytes(16));
    $sql = "INSERT INTO projects (token, customer_name, title, preview_url) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token, $customer_name, $title, $preview_url]);
    $project_id = $pdo->lastInsertId();
    $sql = "INSERT INTO milestones (project_id, title, sort_order) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$project_id, 'Planlægning', 1]);
    $stmt->execute([$project_id, 'Design', 2]);
    $stmt->execute([$project_id, 'Udvikling', 3]);
    $stmt->execute([$project_id, 'Test', 4]);
    $stmt->execute([$project_id, 'Lancering', 5]);
    $link = '../index.html?t=' . $token;
}
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Nyt projekt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="logo">CP</div>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="new-project.php" class="active">Nyt projekt</a>
            <a href="logout.php">Log ud</a>
        </nav>
    </aside>

    <main class="main">
        <h1>Nyt projekt</h1>

        <div class="card">
            <form method="post">
                <label for="customer_name">Kundenavn:</label><br>
                <input type="text" id="customer_name" name="customer_name" required><br>
                <label for="title">Projekttitel:</label><br>
                <input type="text" id="title" name="title" required><br>
                <label for="preview_url">Preview URL:</label><br>
                <input type="text" id="preview_url" name="preview_url"><br>
                <br>
                    <input type="submit" value="Opret Projekt">
            </form>
            <?php if (isset($link)) { echo "<p>Kundelink: $link</p>"; } ?>
        </div>
    </main>
</div>
</body>
</html>