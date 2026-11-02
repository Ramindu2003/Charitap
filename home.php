<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Charitap</title>
    <link rel="stylesheet" href="homecss.css">  <!-- Link to external CSS file -->
</head>
<body>
    <!-- Promotional banner before navigation -->
    <div class="promo-banner">
      🎉 New: Explore events and volunteer opportunities! <a href="search.php">Search Now →</a>
    </div>

    <!-- Modern creative navigation bar -->
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
          <a href="profile.php" class="nav-cta">👤 Profile</a>
        </div>
      </div>
    </nav>
    <section class="hero">
      <div class="hero-content">
        <h2>Connect Donors with Those in Need</h2>
        <p>Make donations faster, clearer, and more impactful.</p>
        <p><a class="btn-hero" href="registration.php">Get Started</a></p>
      </div>
      <picture class="hero-picture">
        <source srcset="assets/images/donate1.jpg" type="image/jpeg">
        <img class="hero-img" src="assets/images/donate1.jpg" alt="Donate">
      </picture>
    </section>
    <section class="categories">
        <div class="category">
          <picture>
            <source srcset="assets/images/money.jpg" type="image/jpeg">
            <img class="category-img" src="assets/images/money.svg" alt="Money">
          </picture>
          <span class="icon dollar"></span>
          <h3>Money</h3>
          <p>Secure digital payments</p>
        </div>
        <div class="category">
          <picture>
            <source srcset="assets/images/foods.jpg" type="image/jpeg">
            <img class="category-img" src="assets/images/food.svg" alt="Food">
          </picture>
          <span class="icon utensil"></span>
          <h3>Food</h3>
          <p>Fresh supplies for all</p>
        </div>
        <div class="category">
          <picture>
            <source srcset="assets/images/clothes.jpg" type="image/jpeg">
            <img class="category-img" src="assets/images/clothes.svg" alt="Clothes">
          </picture>
          <span class="icon tshirt"></span>
          <h3>Clothes</h3>
          <p>Quality items shared</p>
        </div>
        <div class="category">
          <picture>
            <source srcset="assets/images/books1.jpg" type="image/jpeg">
            <img class="category-img" src="assets/images/books.svg" alt="Books">
          </picture>
          <span class="icon book"></span>
          <h3>Books</h3>
          <p>Educational resources</p>
        </div>
    </section>
    <section class="testimonials">
        <h2>What Users Say</h2>
        <div class="testimonial">
            <p>"Charitap made donating so easy!" - Jane D.</p>
        </div>
        <div class="testimonial">
            <p>"Transparent and trustworthy." - Mark S.</p>
        </div>
    </section>
    <footer class="site-footer">

      <div class="container">
        <div class="footer-contact">
          <div class="contact-item">
            <h4>📞 Phone</h4>
            <p><a href="tel:+1-800-555-0123">+94 71555-0123</a></p>
            <p class="muted small">Mon-Fri: 9AM - 6PM</p>
          </div>
          <div class="contact-item">
            <h4>📧 Email</h4>
            <p><a href="mailto:support@charitap.org">support@charitap.org</a></p>
            <p class="muted small">We reply within 24 hours</p>
          </div>
          <div class="contact-item">
            <h4>💬 Quick Support</h4>
            <p><a href="mailto:help@charitap.org">help@charitap.org</a></p>
            <p class="muted small">For urgent issues</p>
          </div>
        </div>

        <hr style="border:none;border-top:1px solid #ddd;margin:24px 0;">
        <p>&copy; 2023 Charitap. All rights reserved.</p>
        <p class="muted small">Made with ♥ — <a href="home.php">Home</a> • <a href="events.php">Events</a> • <a href="results.php">Results</a></p>
      </div>
    </footer>
</body>
</html>
