<?php
session_start();
$config = require __DIR__ . '/../api/config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $config['admin_password']) {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Forkert adgangskode';
}
?>
<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>Log ind</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="app">
    <main class="main">
        <h1>Log ind</h1>
        <div class="card">
            <form method="post">
                <input type="password" name="password" placeholder="Adgangskode" required>
                <input type="submit" value="Log ind">
            </form>
            <?php if ($error) echo "<p>$error</p>"; ?>
        </div>
    </main>
</div>
</body>
</html>