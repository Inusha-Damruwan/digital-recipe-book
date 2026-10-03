<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/page-layout.php';

$recipeId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$recipeId || $recipeId < 1) {
    http_response_code(400);
    crumb_page_header('Recipe not found');
    echo '<section class="page-section"><div class="container page-empty"><h1>Recipe not found</h1><p>Choose a recipe from the community collection.</p><a class="btn btn-orange" href="recipes.php">Browse recipes</a></div></section>';
    crumb_page_footer();
    exit;
}

require_once __DIR__ . '/includes/db.php';
$stmt = $pdo->prepare('SELECT r.id, r.title, r.category, r.ingredients, r.instructions, r.cooking_time, r.image_url, u.username FROM recipes r JOIN users u ON u.id = r.user_id WHERE r.id = ?');
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch();
if (!$recipe) {
    http_response_code(404);
    crumb_page_header('Recipe not found');
    echo '<section class="page-section"><div class="container page-empty"><h1>Recipe not found</h1><p>This recipe may have been removed.</p><a class="btn btn-orange" href="recipes.php">Browse recipes</a></div></section>';
    crumb_page_footer();
    exit;
}

crumb_page_header((string)$recipe['title']);
?>
<section class="page-section">
  <article class="container recipe-detail">
    <a class="text-link" href="recipes.php">← All recipes</a>
    <div class="recipe-detail-grid">
      <div class="recipe-detail-image">
        <img src="<?= crumb_escape($recipe['image_url'] ?: 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=1200&q=85') ?>" alt="<?= crumb_escape($recipe['title']) ?>">
      </div>
      <div class="recipe-detail-copy">
        <p class="section-kicker"><?= crumb_escape(strtoupper($recipe['category'])) ?> · <?= (int)$recipe['cooking_time'] ?> MIN</p>
        <h1 class="page-title"><?= crumb_escape($recipe['title']) ?></h1>
        <p class="recipe-author">Shared by <?= crumb_escape($recipe['username']) ?></p>
        <h2>Ingredients</h2>
        <p class="recipe-preformatted"><?= crumb_escape($recipe['ingredients']) ?></p>
        <h2>Method</h2>
        <p class="recipe-preformatted"><?= crumb_escape($recipe['instructions']) ?></p>
      </div>
    </div>
  </article>
</section>
<?php crumb_page_footer(); ?>
