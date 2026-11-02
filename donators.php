<?php
require __DIR__ . '/config.php';
$config = require __DIR__ . '/config.php';
$mysqli = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donators - Charitap</title>
    <link rel="stylesheet" href="site.css">
    <link rel="stylesheet" href="donators.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <div class="container page-content">
        <div class="donators-hero">
            <h2>Our Donors</h2>
            <p class="muted small">People who make our work possible. Thank you!</p>
            <div class="search">
                <form method="get" action="donators.php">
                    <input name="q" placeholder="Search donors by name or email" value="<?php echo isset($_GET['q'])?htmlspecialchars($_GET['q']):''; ?>">
                </form>
            </div>
        </div>

        <?php
        if ($mysqli->connect_errno) {
            echo '<p class="error">Unable to fetch donators.</p>';
        } else {
            $q = '%' . ($mysqli->real_escape_string($_GET['q'] ?? '')) . '%';
            $stmt = $mysqli->prepare("SELECT name, email, created_at FROM users WHERE user_type = 'donor' AND (name LIKE ? OR email LIKE ?) ORDER BY created_at DESC");
            $stmt->bind_param('ss', $q, $q);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows) {
                echo '<div class="donators-grid">';
                while ($row = $res->fetch_assoc()) {
                    $initial = strtoupper(substr($row['name'],0,1));
                    echo '<div class="card donor-card">';
                    echo '<div class="avatar-large">' . htmlspecialchars($initial) . '</div>';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p class="muted small">' . htmlspecialchars($row['email']) . '</p>';
                    echo '<p class="small">Joined: ' . htmlspecialchars($row['created_at']) . '</p>';
                    echo '<div style="margin-top:10px"><button class="btn btn-primary">Send Thanks</button></div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<p>No donators found yet.</p>';
            }
            $stmt->close();
        }
        $mysqli->close();
        ?>
    </div>
</body>
</html>
