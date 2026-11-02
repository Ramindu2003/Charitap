<?php
// Results page (server-ready). Includes site header/navigation.
// Reads filter params from search.php form submission.
if (session_status() === PHP_SESSION_NONE) session_start();

// Get filter parameters (with safe defaults)
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
$location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';
$date_from = isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : '';

// Build filter summary string
$filters = [];
if ($keyword) $filters[] = "Keyword: <strong>$keyword</strong>";
if ($category) $filters[] = "Category: <strong>" . ucfirst($category) . "</strong>";
if ($location) $filters[] = "Location: <strong>$location</strong>";
if ($date_from) $filters[] = "From: <strong>$date_from</strong>";
if ($date_to) $filters[] = "To: <strong>$date_to</strong>";
$filter_text = !empty($filters) ? implode(" • ", $filters) : "All events";
// Helper: prefer local images in assets/images if present, otherwise fallback to external URL
function choose_image(array $candidates, $fallback) {
  foreach ($candidates as $c) {
    $path = __DIR__ . '/' . $c;
    if (file_exists($path)) return $c;
  }
  return $fallback;
}

// Allow selecting images for cards via upload (input name="card1_file" etc) or via a prior "home" selection stored in session.
// Falls back to local assets/images candidates or external URL.
$uploadDir = __DIR__ . '/assets/uploads';
if (!is_dir($uploadDir)) @mkdir($uploadDir, 0755, true);

$allowedMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

function store_uploaded_image($file, $uploadDir, $allowedMimes) {
  if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
  if (!is_uploaded_file($file['tmp_name'])) return null;
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);
  if (!isset($allowedMimes[$mime])) return null;
  $ext = $allowedMimes[$mime];
  $name = 'card_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
  $dst = rtrim($uploadDir, '/') . '/' . $name;
  if (move_uploaded_file($file['tmp_name'], $dst)) {
    // return relative web path
    return 'assets/uploads/' . $name;
  }
  return null;
}

function get_card_image($fieldName, $sessionKey, array $candidates, $fallback) {
  global $uploadDir, $allowedMimes;
  // 1) If a file was uploaded in this request, store and use it
  if (!empty($_FILES[$fieldName]) && is_array($_FILES[$fieldName])) {
    $saved = store_uploaded_image($_FILES[$fieldName], $uploadDir, $allowedMimes);
    if ($saved) {
      $_SESSION[$sessionKey] = $saved;
      return $saved;
    }
  }

  // 2) If a selection was previously made (e.g., from home section) and kept in session, prefer it
  if (!empty($_SESSION[$sessionKey])) {
    $candidate = $_SESSION[$sessionKey];
    $path = __DIR__ . '/' . $candidate;
    if (file_exists($path)) return $candidate;
    // if session points to an external URL, return it as-is
    if (filter_var($candidate, FILTER_VALIDATE_URL)) return $candidate;
  }

  // 3) Optional: allow selecting an existing asset via GET/POST param (safe basename)
  $param = null;
  if (!empty($_REQUEST[$fieldName])) $param = basename($_REQUEST[$fieldName]);
  if ($param) {
    $rel = 'assets/images/' . $param;
    if (file_exists(__DIR__ . '/' . $rel)) {
      $_SESSION[$sessionKey] = $rel;
      return $rel;
    }
  }

  // 4) Default choose_image behavior
  return choose_image($candidates, $fallback);
}

// Card image assignments (will check uploads, session selection, request param, then assets, then external)
$card1_img = get_card_image('card1_file', 'card1_img', [
  'assets/images/Communites_donation_drive.jpg', 'assets/images/Communites_donation_drive.png', 'assets/images/Communites_donation_drive.webp'
], 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=60');

$card2_img = get_card_image('card2_file', 'card2_img', [
  'assets/images/neighborhood_cleanup.jpg', 'assets/images/neighborhood_cleanup.png', 'assets/images/neighborhood_cleanup.webp'
], 'https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d?auto=format&fit=crop&w=800&q=60');

$card3_img = get_card_image('card3_file', 'card3_img', [
  'assets/images/Holiday_Fundraiser_Gala.jpg', 'assets/images/Holiday_Fundraiser_Gala.png', 'assets/images/Holiday_Fundraiser_Gala.webp'
], 'https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf?auto=format&fit=crop&w=800&q=60');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Results — CHARITAP</title>
    <link rel="stylesheet" href="site.css" />
    <link rel="stylesheet" href="results.css" />
  </head>
  <body>
    <?php include 'header.php'; ?>

    <main class="results-page creative">
      <!-- Filter summary section -->
      <section class="results-summary">
        <p class="summary-text">
          Showing results for: <?php echo $filter_text; ?>
          <a href="search.php" class="link-modify">Modify search</a>
        </p>
      </section>

      <section class="results-grid">
        <!-- Card 1 -->
        <article class="result-card">
          <div class="card-media">
            <img src="<?php echo htmlspecialchars($card1_img); ?>" alt="Donation event" />
            <span class="badge">Featured</span>
            <span class="goal">85% funded</span>
          </div>
          <div class="card-content">
            <h2 class="card-title">Community Donation Drive</h2>
            <p class="card-meta">Dec 5, 2025 • Town Hall</p>
            <p class="card-excerpt">A large-scale drive accepting food and clothing donations. Volunteers welcome.</p>
            <div class="card-footer">
              <div class="progress" aria-hidden>
                <div class="progress-fill" style="width:85%"></div>
              </div>
              <div class="cta">
                <a class="btn btn-glow" href="#">View</a>
                <a class="btn btn-ghost" href="#">Save</a>
              </div>
            </div>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="result-card">
          <div class="card-media">
            <img src="<?php echo htmlspecialchars($card2_img); ?>" alt="Volunteer event" />
            <span class="badge small">Volunteer</span>
          </div>
          <div class="card-content">
            <h2 class="card-title">Neighborhood Cleanup</h2>
            <p class="card-meta">Nov 29, 2025 • Riverside Park</p>
            <p class="card-excerpt">Join neighbors for a morning clean-up. Tools and refreshments provided.</p>
            <div class="card-footer">
              <div class="meta-tags">Free • Open to all</div>
              <div class="cta">
                <a class="btn btn-outline" href="#">Details</a>
              </div>
            </div>
          </div>
        </article>



        <!-- Card 4 (example minimal) -->
        <article class="result-card">
          <div class="card-media">
            <img src="<?php echo htmlspecialchars($card3_img); ?>" alt="Fundraiser" />
            <span class="badge small">Fundraiser</span>
          </div>
          <div class="card-content">
            <h2 class="card-title">Holiday Fundraiser Gala</h2>
            <p class="card-meta">Dec 20, 2025 • Grand Hall</p>
            <p class="card-excerpt">An evening of music and donations to support local shelters.</p>
            <div class="card-footer">
              <div class="meta-tags">Tickets: Paid</div>
              <div class="cta">
                <a class="btn btn-outline" href="#">Tickets</a>
              </div>
            </div>
          </div>
        </article>

      </section>
    </main>

    <?php
    // Defensive include: only include footer if present to avoid runtime warnings.
    if (file_exists(__DIR__ . '/footer.php')) {
        include_once __DIR__ . '/footer.php';
    } else {
        // Fallback inline footer to avoid broken layout when footer.php is missing
        echo '<footer class="site-footer"><div class="container"><p>&copy; ' . date("Y") . ' CHARITAP</p></div></footer>';
    }
    ?>
  </body>
</html>
