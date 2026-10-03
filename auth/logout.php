<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';
start_app_session();
require_once __DIR__ . '/../includes/page-layout.php';

crumb_page_header('Log out', '../', '../api.php');
?>
<section class="page-section auth-page">
  <div class="container narrow-container page-empty">
    <p class="section-kicker">UNTIL NEXT TIME</p>
    <h1 class="page-title">Leaving the <em>kitchen?</em></h1>
    <p class="section-description">You can come back whenever you're hungry for inspiration.</p>
    <form id="logoutForm">
      <button class="btn btn-orange" type="submit">Log out <span aria-hidden="true">↗</span></button>
      <p class="auth-feedback page-feedback" id="logoutFeedback" aria-live="polite"></p>
    </form>
  </div>
</section>
<?php crumb_page_footer('../'); ?>
