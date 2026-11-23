<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$pdo = require __DIR__ . '/../api/db.php';
$id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'status') {
        $sql = "UPDATE projects SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['status'], $id]);
    }

    if ($_POST['action'] === 'milestone') {
        $sql = "UPDATE milestones SET done = NOT done WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['milestone_id']]);
    }

    if ($_POST['action'] === 'waiting') {
        $sql = "UPDATE projects SET waiting_on_client = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['waiting_on_client'], $id]);
    }

    if ($_POST['action'] === 'update') {
        $sql = "INSERT INTO updates (project_id, message) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $_POST['message']]);
    }
}

$stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT id, title, done FROM milestones WHERE project_id = ? ORDER BY sort_order";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$milestones = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT id, project_id, message, created_at FROM updates WHERE project_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$updates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Rediger projekt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="logo">CP</div>
        <nav>
            <a href="index.php">Dashboard</a>
            <a href="new-project.php">Nyt projekt</a>
        </nav>
    </aside>

    <main class="main">
        <h1><?php echo $project['title']; ?></h1>

        <div class="card">
            <h2>Kundelink</h2>
            <input type="text" value="<?php echo '../index.html?t=' . $project['token']; ?>" id="customerLink">
            <button onclick="myFunction()">Kopiér</button>
        </div>

        <div class="card">
            <h2>Status</h2>
            <form method="post">
                <input type="hidden" name="action" value="status">
                <select name="status" id="status">
                    <option value="planned">Planned</option>
                    <option value="progress">Progress</option>
                    <option value="waiting">Waiting</option>
                    <option value="done">Done</option>
                </select>
                <input type="submit" value="Gem">
            </form>
        </div>

        <div class="card">
            <h2>Milepæle</h2>
            <?php
            foreach ($milestones as $value) {
                echo '<form method="post">';
                echo '<input type="hidden" name="action" value="milestone">';
                echo '<input type="hidden" name="milestone_id" value="' . $value['id'] . '">';
                echo '<button type="submit">' . ($value['done'] ? '✓' : '○') . ' ' . $value['title'] . '</button>';
                echo '</form>';
            }
            ?>
        </div>

        <div class="card">
            <h2>Venter på kunden</h2>
            <form method="post">
                <input type="hidden" name="action" value="waiting">
                <textarea name="waiting_on_client" rows="4" cols="50"><?php echo $project['waiting_on_client']; ?></textarea>
                <input type="submit" value="Gem">
            </form>
        </div>

        <div class="card">
            <h2>Ny opdatering</h2>
            <form method="post">
                <input type="hidden" name="action" value="update">
                <textarea name="message" rows="3" cols="50" required></textarea>
                <input type="submit" value="Post">
            </form>
        </div>
    </main>
</div>

<script>
    function myFunction() {
        var copyText = document.getElementById("customerLink");

        copyText.select();
        copyText.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(copyText.value);

        alert("Kopieret: " + copyText.value);
    }
</script>
</body>
</html>