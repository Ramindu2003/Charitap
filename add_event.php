<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: events.php');
    exit;
}
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$date = $_POST['event_date'] ?? '';

if ($title === '' || $date === '') {
    header('Location: events.php');
    exit;
}

$config = require __DIR__ . '/config.php';
$mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
if ($mysqli->connect_errno) {
    header('Location: events.php');
    exit;
}

$stmt = $mysqli->prepare('INSERT INTO events (title, description, event_date, created_by, created_at) VALUES (?, ?, ?, ?, NOW())');
$stmt->bind_param('sssi', $title, $desc, $date, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$mysqli->close();

header('Location: events.php');
exit;
