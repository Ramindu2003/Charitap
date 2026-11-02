<?php
// Search/filter landing page that feeds into results.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Search Events — CHARITAP</title>
    <link rel="stylesheet" href="site.css" />
    <link rel="stylesheet" href="search.css" />
  </head>
  <body>
    <?php include 'header.php'; ?>

    <main class="search-page">
      <section class="search-hero">
        <h1>Find Your Cause</h1>
        <p>Browse events, donation drives, and volunteer opportunities in your area.</p>
      </section>

      <section class="search-form-container">
        <form class="search-form" method="GET" action="results.php">
          <fieldset>
            <legend>Filter by</legend>
            
            <div class="form-group">
              <label for="keyword">Keyword</label>
              <input 
                type="text" 
                id="keyword" 
                name="keyword" 
                placeholder="e.g., donation, volunteer, food..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"
              />
            </div>

            <div class="form-group">
              <label for="category">Category</label>
              <select id="category" name="category">
                <option value="">All Categories</option>
                <option value="donation" <?php echo (isset($_GET['category']) && $_GET['category'] === 'donation') ? 'selected' : ''; ?>>Donation</option>
                <option value="volunteer" <?php echo (isset($_GET['category']) && $_GET['category'] === 'volunteer') ? 'selected' : ''; ?>>Volunteer</option>
                <option value="fundraiser" <?php echo (isset($_GET['category']) && $_GET['category'] === 'fundraiser') ? 'selected' : ''; ?>>Fundraiser</option>
                <option value="workshop" <?php echo (isset($_GET['category']) && $_GET['category'] === 'workshop') ? 'selected' : ''; ?>>Workshop</option>
              </select>
            </div>

            <div class="form-group">
              <label for="location">Location</label>
              <input 
                type="text" 
                id="location" 
                name="location" 
                placeholder="e.g., New York, Remote..."
                value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>"
              />
            </div>

            <div class="form-group">
              <label for="date_from">From Date</label>
              <input 
                type="date" 
                id="date_from" 
                name="date_from"
                value="<?php echo isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : ''; ?>"
              />
            </div>

            <div class="form-group">
              <label for="date_to">To Date</label>
              <input 
                type="date" 
                id="date_to" 
                name="date_to"
                value="<?php echo isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : ''; ?>"
              />
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Search</button>
              <a href="search.php" class="btn btn-secondary">Clear</a>
            </div>
          </fieldset>
        </form>
      </section>

      <section class="quick-links">
        <h2>Popular Searches</h2>
        <div class="quick-buttons">
          <a href="results.php?category=donation" class="quick-btn">Donation Drives</a>
          <a href="results.php?category=volunteer" class="quick-btn">Volunteer Opportunities</a>
          <a href="results.php?category=fundraiser" class="quick-btn">Fundraisers</a>
          <a href="results.php?category=workshop" class="quick-btn">Workshops</a>
        </div>
      </section>
    </main>

    <?php
    if (file_exists(__DIR__ . '/footer.php')) {
        include_once __DIR__ . '/footer.php';
    } else {
        echo '<footer class="site-footer"><div class="container"><p>&copy; ' . date("Y") . ' CHARITAP</p></div></footer>';
    }
    ?>
  </body>
</html>
