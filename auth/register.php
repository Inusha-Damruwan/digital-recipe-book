<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
start_app_session();
if (!empty($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}
require_once __DIR__ . '/../includes/page-layout.php';

crumb_page_header('Join the kitchen', '../', '../api.php');
?>
<section class="page-section auth-page">
  <div class="container narrow-container">
    <p class="section-kicker">A PLACE AT THE TABLE</p>
    <h1 class="page-title">Join the <em>kitchen.</em></h1>
    <p class="section-description">Create an account and share a favorite recipe.</p>
    <form class="page-form" id="registerForm" novalidate>
      <label for="registerUsername">Username</label>
      <input type="text" id="registerUsername" name="username" minlength="3" maxlength="50" autocomplete="username" required>
      <label for="registerEmail">Email address</label>
      <input type="email" id="registerEmail" name="email" maxlength="190" autocomplete="email" required>
      <label for="registerPassword">Password <span class="input-hint">(at least 8 characters)</span></label>
      <input type="password" id="registerPassword" name="password" minlength="8" autocomplete="new-password" required>
      <button class="btn btn-orange" type="submit">Create account <span aria-hidden="true">↗</span></button>
      <p class="auth-feedback page-feedback" id="registerFeedback" aria-live="polite"></p>
    </form>
    <p class="auth-page-switch">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</section>
<?php crumb_page_footer('../'); ?>
