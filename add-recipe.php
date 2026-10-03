<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
start_app_session();
if (empty($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}
require_once __DIR__ . '/includes/page-layout.php';

crumb_page_header('Share a recipe');
?>
<section class="page-section">
  <div class="container narrow-container">
    <p class="section-kicker">ADD YOUR LITTLE SOMETHING</p>
    <h1 class="page-title">Share a <em>recipe.</em></h1>
    <p class="section-description">Pass a favorite along to the Crumb &amp; Herb community.</p>
    <form class="page-form" id="addRecipeForm" novalidate>
      <label for="recipeTitle">Recipe name</label>
      <input type="text" id="recipeTitle" name="title" maxlength="150" required>
      <label for="recipeCategory">Category</label>
      <select id="recipeCategory" name="category" required>
        <option value="">Choose a category</option>
        <option>Breakfast</option><option>Lunch</option><option>Dinner</option><option>Dessert</option><option>Snack</option><option>Drinks</option>
      </select>
      <label for="recipeTime">Cooking time (minutes)</label>
      <input type="number" id="recipeTime" name="cooking_time" min="1" max="1440" required>
      <label for="recipeImage">Image URL <span class="input-hint">(optional)</span></label>
      <input type="url" id="recipeImage" name="image_url" maxlength="2048" placeholder="https://">
      <label for="recipeIngredients">Ingredients</label>
      <textarea id="recipeIngredients" name="ingredients" rows="5" required></textarea>
      <label for="recipeInstructions">Instructions</label>
      <textarea id="recipeInstructions" name="instructions" rows="6" required></textarea>
      <button class="btn btn-orange" type="submit">Publish recipe <span aria-hidden="true">↗</span></button>
      <p class="form-feedback page-feedback" id="recipeFeedback" aria-live="polite"></p>
    </form>
  </div>
</section>
<?php crumb_page_footer(); ?>
