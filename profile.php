<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/config.php';
$config = require __DIR__ . '/config.php';
$mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

// Fetch user
$user = null;
if (!$mysqli->connect_errno) {
    $stmt = $mysqli->prepare('SELECT name, email, user_type, created_at FROM users WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
}

// Fetch recent events created by this user
$recentEvents = [];
$events_error = null;
if (!$mysqli->connect_errno) {
    // Check whether the events table exists
    $check = $mysqli->query("SHOW TABLES LIKE 'events'");
    if (!$check || $check->num_rows == 0) {
        // Try to create the events table automatically
        $createTableSql = "CREATE TABLE IF NOT EXISTS events (
          id INT AUTO_INCREMENT PRIMARY KEY,
          title VARCHAR(255) NOT NULL,
          description TEXT,
          event_date DATE NOT NULL,
          created_by INT NOT NULL,
          created_at DATETIME NOT NULL,
          FOREIGN KEY (created_by) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        if (!$mysqli->query($createTableSql)) {
            $events_error = 'Events table missing and automatic creation failed: ' . htmlspecialchars($mysqli->error);
        }
    }

    if (!$events_error) {
        $stmt = $mysqli->prepare('SELECT id, title, event_date FROM events WHERE created_by = ? ORDER BY event_date DESC LIMIT 5');
        if ($stmt) {
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $recentEvents[] = $r;
            $stmt->close();
        } else {
            $events_error = 'Unable to query events: ' . htmlspecialchars($mysqli->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile — Charitap</title>
    <link rel="stylesheet" href="site.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">

                <!-- New prominent Edit Profile section -->
                <div class="card" style="max-width:500px;margin:32px auto 24px;box-shadow:0 4px 18px rgba(128,0,128,0.08);background:#fff;">
                    <h2 style="color:#800080;margin-bottom:18px;">Edit Your Profile</h2>
                    <form method="post" action="update_profile.php">
                        <div class="field">
                            <label for="name">Full name</label>
                            <input id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>
                        <div class="field">
                            <label for="type">User type</label>
                            <select id="type" name="user_type">
                                <option value="donor" <?php echo (isset($user['user_type']) && $user['user_type']=='donor') ? 'selected' : ''; ?>>Donor</option>
                                <option value="recipient" <?php echo (isset($user['user_type']) && $user['user_type']=='recipient') ? 'selected' : ''; ?>>Recipient</option>
                            </select>
                        </div>
                        <div style="display:flex; gap:8px; margin-top:18px;">
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                            <a class="btn btn-ghost" href="logout.php">Logout</a>
                        </div>
                    </form>
                </div>

                <div class="profile-header">
                        <div class="avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? '',0,1) ?: 'U'); ?></div>
                        <div class="profile-info">
                                <h1><?php echo htmlspecialchars($user['name'] ?? ($_SESSION['user_name'] ?? 'User')); ?></h1>
                                <p class="muted"><?php echo htmlspecialchars($user['email'] ?? ($_SESSION['user_email'] ?? '')); ?></p>
                                <div class="profile-actions">
                                        <a class="btn btn-ghost" href="events.php">My Events</a>
                                </div>
                        </div>
                        <div>
                                <p class="small muted">Member since</p>
                                <p><?php echo htmlspecialchars($user['created_at'] ?? ''); ?></p>
                        </div>
                </div>

        <div class="grid">
            <div>
                <div class="card">
                    <h3>About</h3>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($user['user_type'] ?? ''); ?></p>
                    <p class="muted small">Update your display name or user type below.</p>
                </div>

                <div class="card">
                    <h3>Recent Events You Created</h3>
                    <?php if (count($recentEvents)): ?>
                        <ul class="events-list">
                        <?php foreach ($recentEvents as $ev): ?>
                            <li><strong><?php echo htmlspecialchars($ev['title']); ?></strong><br>
                                <span class="muted small"><?php echo htmlspecialchars($ev['event_date']); ?></span></li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="muted">You haven't created any events yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="card">
                    <h3>Edit Profile</h3>
                    <?php if (isset($_GET['updated'])): ?>
                        <p class="success">Profile updated successfully.</p>
                    <?php endif; ?>
                    <form method="post" action="update_profile.php">
                        <div class="field">
                            <label for="name">Full name</label>
                            <input id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                        </div>
                        <div class="field">
                            <label for="type">User type</label>
                            <select id="type" name="user_type">
                                <option value="donor" <?php echo (isset($user['user_type']) && $user['user_type']=='donor') ? 'selected' : ''; ?>>Donor</option>
                                <option value="recipient" <?php echo (isset($user['user_type']) && $user['user_type']=='recipient') ? 'selected' : ''; ?>>Recipient</option>
                            </select>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button class="btn btn-primary" type="submit">Save</button>
                            <a class="btn btn-ghost" href="logout.php">Logout</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
