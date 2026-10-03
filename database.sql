CREATE DATABASE IF NOT EXISTS digital_recipe_book
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digital_recipe_book;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS recipes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(30) NOT NULL,
  ingredients TEXT NOT NULL,
  instructions TEXT NOT NULL,
  cooking_time SMALLINT UNSIGNED NOT NULL,
  image_url VARCHAR(2048) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_recipes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_recipes_category (category),
  INDEX idx_recipes_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sample recipes use a dedicated account with a random, unavailable password.
-- The insert is repeatable and leaves an existing account unchanged.
INSERT INTO users (username, email, password)
VALUES (
  'crumb_herb_samples',
  'recipe-samples@crumbherbfoods.lk',
  '$2y$10$gDoIl1PuONepgAqK.OZPo.C1HFkpXwRLns.4JnQC8Je4LcI28bQg2'
)
ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);

SET @sample_recipe_user_id = LAST_INSERT_ID();

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Creamy Garden Pasta', 'Dinner',
  'Pasta, cream, garlic, olive oil, grated Parmesan, and fresh herbs.',
  'Cook the pasta in salted water until al dente. Warm olive oil and garlic in a pan, stir in cream and Parmesan, then toss with the drained pasta. Finish with fresh herbs and serve warm.',
  25, 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Creamy Garden Pasta');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Berry Morning Bowl', 'Breakfast',
  'Rolled oats, yogurt, mixed berries, honey, and toasted nuts or seeds.',
  'Spoon yogurt into a bowl and top with rolled oats and fresh berries. Drizzle with honey and finish with toasted nuts or seeds.',
  10, 'https://images.unsplash.com/photo-1490351267196-b7a67e26e41b?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Berry Morning Bowl');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Green Goddess Salad', 'Lunch',
  'Mixed greens, cucumber, avocado, herbs, lemon juice, olive oil, and plain yogurt.',
  'Whisk chopped herbs with lemon juice, olive oil, and yogurt to make the dressing. Toss the greens and sliced vegetables with the dressing, then top with avocado and serve.',
  15, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Green Goddess Salad');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Sunday Lemon Cake', 'Dessert',
  'Flour, sugar, butter, eggs, lemon zest, lemon juice, baking powder, and milk.',
  'Cream butter and sugar, beat in eggs, then fold in flour and baking powder with milk, lemon zest, and juice. Bake in a greased cake tin at 175 C until golden and a skewer comes out clean. Cool before serving.',
  50, 'https://images.unsplash.com/photo-1519915028121-7d3463d20b13?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Sunday Lemon Cake');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Avocado Toast', 'Breakfast',
  'Sourdough or country bread, ripe avocado, lemon juice, salt, pepper, and fresh herbs.',
  'Toast the bread until crisp. Mash avocado with lemon juice, salt, and pepper, spread over the toast, then finish with fresh herbs.',
  12, 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Avocado Toast');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Roasted Veggie Bowl', 'Lunch',
  'Seasonal vegetables, cooked grains, olive oil, salt, pepper, and a dressing of your choice.',
  'Cut the vegetables into bite-size pieces, toss with olive oil, salt, and pepper, then roast at 200 C until tender and browned. Serve over warm cooked grains with dressing.',
  30, 'https://images.unsplash.com/photo-1511690743698-d9d85f2fbf38?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Roasted Veggie Bowl');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Tomato Basil Pasta', 'Dinner',
  'Pasta, ripe tomatoes, garlic, olive oil, fresh basil, salt, and black pepper.',
  'Cook pasta until al dente. Gently cook garlic and chopped tomatoes in olive oil until saucy. Toss with drained pasta, season to taste, and finish with fresh basil.',
  20, 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Tomato Basil Pasta');

INSERT INTO recipes (user_id, title, category, ingredients, instructions, cooking_time, image_url)
SELECT @sample_recipe_user_id, 'Strawberry Shortcake', 'Dessert',
  'Shortcake or sponge cake, fresh strawberries, sugar, and whipped cream.',
  'Slice the strawberries and toss with a little sugar. Layer shortcake with whipped cream and strawberries, then serve chilled.',
  45, 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=800&q=85'
WHERE NOT EXISTS (SELECT 1 FROM recipes WHERE title = 'Strawberry Shortcake');

-- New user accounts can still be created through the website registration form.
