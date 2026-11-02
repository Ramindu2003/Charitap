<?php
session_start();
require __DIR__ . '/config.php';
$config = require __DIR__ . '/config.php';
$mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Charitap</title>
    <link rel="stylesheet" href="site.css">
    <link rel="stylesheet" href="events.css">
  </head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <div class="container page-content">
        <h2>Upcoming Events</h2>
        <?php
        if ($mysqli->connect_errno) {
            echo '<p class="error">Unable to fetch events.</p>';
        } else {
            $res = $mysqli->query('SELECT id, title, description, event_date, created_by FROM events ORDER BY event_date ASC');
            if ($res && $res->num_rows) {
                echo '<ul class="events-list">';
                while ($row = $res->fetch_assoc()) {
                    echo '<li><strong>' . htmlspecialchars($row['title']) . '</strong> - ' . htmlspecialchars($row['event_date']) . '<br>' . nl2br(htmlspecialchars($row['description'])) . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No upcoming events.</p>';
            }
        }

        // Simple form to add an event for logged-in users
        if (!empty($_SESSION['user_id'])) {
            ?>
            <div class="card" style="margin-top:18px">
              <h3>Create an Event</h3>
              <form method="post" action="add_event.php">
                <div class="field"><input type="text" name="title" placeholder="Event title" required></div>
                <div class="field"><textarea name="description" placeholder="Description"></textarea></div>
                <div class="field"><input type="date" name="event_date" required></div>
                <div class="text-right"><button class="btn btn-primary" type="submit">Create Event</button></div>
              </form>
            </div>
            <?php
        } else {
            echo '<p><a href="login.php">Log in</a> to create events.</p>';
        }

        $mysqli->close();
        ?>
    </div>
</body>
</html>
