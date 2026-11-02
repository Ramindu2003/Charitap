<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$user_type = ($_POST['user_type'] ?? 'donor');

$config = require __DIR__ . '/config.php';
$mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
if ($mysqli->connect_errno) {
    header('Location: profile.php');
    exit;
}

$stmt = $mysqli->prepare('UPDATE users SET name = ?, user_type = ? WHERE id = ?');
$stmt->bind_param('ssi', $name, $user_type, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$mysqli->close();

// update session name
$_SESSION['user_name'] = $name;

header('Location: profile.php?updated=1');
exit;
