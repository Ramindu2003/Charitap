<?php
// Reusable header/navigation - modern creative navbar
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="creative-nav">
  <div class="creative-nav-inner">
    <a href="home.php" class="creative-nav-brand">💚 Charitap</a>
    <ul class="creative-nav-links">
      <li><a href="home.php">🏠 Home</a></li>
      <li><a href="events.php">📅 Events</a></li>
      <li><a href="donators.php">👥 Donators</a></li>
      <li><a href="search.php">🔍 Search</a></li>
      <li><a href="results.php">📊 Results</a></li>
    </ul>
    <div class="creative-nav-right">
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="profile.php" class="nav-cta" title="Your profile">👤 Profile</a>
        <span class="muted small">Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
        <a href="logout.php" class="nav-cta">Logout</a>
      <?php else: ?>
        <a href="login.php" class="nav-cta">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
