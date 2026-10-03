<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/page-layout.php';
require_once __DIR__ . '/includes/db.php';

$category = trim((string)($_GET['category'] ?? ''));
$allowedCategories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snack', 'Drinks'];
if ($category !== '' && !in_array($category, $allowedCategories, true)) {
    http_response_code(400);
    $category = '';
}

if ($category === '') {
    $stmt = $pdo->query('SELECT r.id, r.title, r.category, r.cooking_time, r.image_url, u.username FROM recipes r JOIN users u ON u.id = r.user_id ORDER BY r.created_at DESC, r.id DESC');
} else {
    $stmt = $pdo->prepare('SELECT r.id, r.title, r.category, r.cooking_time, r.image_url, u.username FROM recipes r JOIN users u ON u.id = r.user_id WHERE r.category = ? ORDER BY r.created_at DESC, r.id DESC');
    $stmt->execute([$category]);
}
$recipes = $stmt->fetchAll();

crumb_page_header('Recipes');
?>
<section class="page-section">
  <div class="container">
    <p class="section-kicker">FROM OUR COMMUNITY KITCHEN</p>
    <h1 class="page-title">Recipes <em>to love.</em></h1>
    <p class="section-description">Browse home-cooked inspiration shared by the Crumb &amp; Herb community.</p>
    <?php if ($category !== ''): ?>
      <p class="page-filter">Showing <?= crumb_escape($category) ?> recipes · <a href="recipes.php">View all</a></p>
    <?php endif; ?>

    <?php if ($recipes): ?>
      <div class="row g-4 recipe-list">
        <?php foreach ($recipes as $index => $recipe): ?>
          <div class="col-sm-6 col-lg-4">
            <a class="recipe-card-link database-recipe-link" href="recipe-details.php?id=<?= (int)$recipe['id'] ?>">
              <article class="recipe-card database-recipe-card">
                <div class="recipe-image">
                  <img src="<?= crumb_escape($recipe['image_url'] ?: 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=900&q=85') ?>" alt="<?= crumb_escape($recipe['title']) ?>" loading="lazy">
                </div>
                <div class="recipe-meta"><span><span class="recipe-number"><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span> <?= crumb_escape(strtoupper($recipe['category'])) ?></span><span>◷ <?= (int)$recipe['cooking_time'] ?> MIN</span></div>
                <h2 class="database-recipe-title"><?= crumb_escape($recipe['title']) ?></h2>
                <p class="recipe-author">Shared by <?= crumb_escape($recipe['username']) ?></p>
              </article>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="page-empty">
        <h2>No community recipes yet</h2>
        <p>Be the first to add something delicious to the table.</p>
        <a class="btn btn-orange" href="add-recipe.php">Share a recipe <span aria-hidden="true">↗</span></a>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php crumb_page_footer(); ?>
