<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
start_app_session();
if (!empty($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}
require_once __DIR__ . '/../includes/page-layout.php';

crumb_page_header('Login', '../', '../api.php');
?>
<section class="page-section auth-page">
  <div class="container narrow-container">
    <p class="section-kicker">WELCOME BACK</p>
    <h1 class="page-title">Come <em>on in.</em></h1>
    <p class="section-description">Sign in to find your recipes and share something lovely.</p>
    <form class="page-form" id="loginForm" novalidate>
      <label for="loginEmail">Email address</label>
      <input type="email" id="loginEmail" name="email" autocomplete="email" required>
      <label for="loginPassword">Password</label>
      <input type="password" id="loginPassword" name="password" autocomplete="current-password" required>
      <button class="btn btn-orange" type="submit">Log in <span aria-hidden="true">↗</span></button>
      <p class="auth-feedback page-feedback" id="loginFeedback" aria-live="polite"></p>
    </form>
    <p class="auth-page-switch">New to the kitchen? <a href="register.php">Create an account</a></p>
  </div>
</section>
<?php crumb_page_footer('../'); ?>
