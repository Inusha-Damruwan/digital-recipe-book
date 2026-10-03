<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
start_app_session();
if (empty($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}
require_once __DIR__ . '/includes/page-layout.php';
require_once __DIR__ . '/includes/db.php';

$user = current_user($pdo);
if (!$user) {
    $_SESSION = [];
    header('Location: auth/login.php');
    exit;
}
$stmt = $pdo->prepare('SELECT id, title, category, cooking_time FROM recipes WHERE user_id = ? ORDER BY created_at DESC, id DESC');
$stmt->execute([$user['id']]);
$recipes = $stmt->fetchAll();

crumb_page_header('Your kitchen');
?>
<section class="page-section">
  <div class="container">
    <div class="dashboard-heading">
      <div><p class="section-kicker">YOUR LITTLE CORNER</p><h1 class="page-title">Welcome, <em><?= crumb_escape($user['username']) ?>.</em></h1><p class="section-description">Your shared recipes, all in one place.</p></div>
      <a class="btn btn-orange" href="add-recipe.php">Add a recipe <span aria-hidden="true">↗</span></a>
    </div>
    <?php if ($recipes): ?>
      <div class="dashboard-list">
        <?php foreach ($recipes as $recipe): ?>
          <a class="dashboard-recipe" href="recipe-details.php?id=<?= (int)$recipe['id'] ?>">
            <span><strong><?= crumb_escape($recipe['title']) ?></strong><small><?= crumb_escape($recipe['category']) ?></small></span>
            <span><?= (int)$recipe['cooking_time'] ?> min <span aria-hidden="true">↗</span></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="page-empty"><h2>Your recipe box is waiting</h2><p>Share your first recipe and give someone a new favorite.</p><a class="btn btn-orange" href="add-recipe.php">Add your first recipe</a></div>
    <?php endif; ?>
  </div>
</section>
<?php crumb_page_footer(); ?>
