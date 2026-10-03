<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';

function crumb_escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function crumb_page_header(string $title, string $assetPrefix = '', string $apiPath = 'api.php'): void {
    start_app_session();
    $title = crumb_escape($title);
    $assetPrefix = crumb_escape($assetPrefix);
    $apiPath = crumb_escape($apiPath);
    $isLoggedIn = !empty($_SESSION['user_id']);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f8f7f1">
  <title><?= $title ?> | Crumb &amp; Herb</title>
  <link rel="canonical" href="https://crumbherbfoods.lk/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $assetPrefix ?>css/style.css">
</head>
<body data-api-url="<?= $apiPath ?>">
  <nav class="navbar navbar-expand-lg site-nav sticky-top" aria-label="Main navigation">
    <div class="container">
      <a class="navbar-brand brand" href="<?= $assetPrefix ?>index.php"><span class="brand-mark" aria-hidden="true">✳</span> crumb &amp; herb</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="<?= $assetPrefix ?>index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $assetPrefix ?>recipes.php">Recipes</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $assetPrefix ?>index.php#about">Our Story</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $assetPrefix ?>contact.php">Contact</a></li>
        </ul>
        <div class="nav-actions">
          <?php if ($isLoggedIn): ?>
            <a class="login-link" href="<?= $assetPrefix ?>dashboard.php">My kitchen</a>
            <a class="btn btn-orange nav-cta" href="<?= $assetPrefix ?>auth/logout.php">Log out</a>
          <?php else: ?>
            <a class="login-link" href="<?= $assetPrefix ?>auth/login.php">Login</a>
            <a class="btn btn-orange nav-cta" href="<?= $assetPrefix ?>auth/register.php">Join the kitchen <span aria-hidden="true">↗</span></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
  <main class="page-main">
<?php
}

function crumb_page_footer(string $assetPrefix = ''): void {
    $assetPrefix = crumb_escape($assetPrefix);
    ?>
  </main>
  <footer class="site-footer">
    <div class="container">
      <div class="footer-inner">
        <div class="footer-brand-block">
          <a class="brand footer-brand" href="<?= $assetPrefix ?>index.php"><span class="brand-mark" aria-hidden="true">✳</span> crumb &amp; herb</a>
          <p>Made with care, served with joy.</p>
        </div>
        <div class="footer-contact">
          <a href="tel:+94702936583">0702936583</a>
          <a href="mailto:ipdamruwan2831@gmail.com">ipdamruwan2831@gmail.com</a>
          <a href="https://maps.google.com/?q=22%2C+Punchi+Akurugoda%2C+Tissamaharama%2C+Sri+Lanka" target="_blank" rel="noopener noreferrer">22, Punchi Akurugoda, Tissamaharama, Sri Lanka</a>
          <a href="https://crumbherbfoods.lk/" target="_blank" rel="noopener noreferrer">crumbherbfoods.lk</a>
        </div>
      </div>
      <p class="footer-copyright">© 2026 Crumb &amp; Herb Recipe Book</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $assetPrefix ?>js/script.js"></script>
</body>
</html>
<?php
}
